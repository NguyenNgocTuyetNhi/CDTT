<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function show()
    {
        // Kiểm tra nếu quay về từ MoMo thành công
        if (request('resultCode') === '0') {
            return $this->processMomoSuccess();
        }

        // Lấy giỏ hàng từ Cart model thay vì session
            $cartItems = collect();
            if (Auth::check()) {
                $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
            } else {
                $sessionId = session()->getId();
            $cartItems = Cart::where('sessionId', $sessionId)->with('product')->get();
        }
        
        $total = 0;
        $cart = [];
        
        foreach ($cartItems as $item) {
            $cart[] = [
                'id' => $item->product->id,
                'name' => $item->product->name,
                'thumbnail' => $item->product->image,
                'qty' => $item->quantity,
                'price_buy' => $item->product->price,
                'stock' => $item->product->stock
            ];
            $total += $item->product->price * $item->quantity;
        }
        
        return view('shop.checkout', compact('cart', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'payment' => 'required|in:COD,MOMO'
        ]);

        $userId = Auth::id();
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();

            if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng trống'
            ], 400);
            }

        $total = $cartItems->sum(function($item) {
                return $item->product->price * $item->quantity;
            });

        try {
            if ($request->payment === 'COD') {
                DB::beginTransaction();

            // Create order
            $order = Order::create([
                    'user_id' => $userId,
                'order_number' => 'ORD-' . time(),
                    'customer_name' => $request->name,
                    'customer_email' => $request->email,
                    'customer_phone' => $request->phone,
                    'customer_address' => $request->address,
                    'payment_method' => 'COD',
                    'subtotal' => $total,
                    'shipping_fee' => 0,
                    'tax' => 0,
                'total' => $total,
                    'notes' => '',
                'status' => 'pending'
            ]);

                // Create order details
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'total' => $item->product->price * $item->quantity
                ]);

                    // Cập nhật số lượng sản phẩm
                    $product = $item->product;
                    $product->stock -= $item->quantity;
                    $product->save();
            }

            DB::commit();

                // Xóa giỏ hàng
                Cart::where('user_id', $userId)->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Đặt hàng thành công',
                    'redirect' => route('checkout.success', $order->id)
                ]);

            } elseif ($request->payment === 'MOMO') {
                // Lưu thông tin đơn hàng tạm thời vào session
                $pendingOrderData = [
                    'user_id' => $userId,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'payment' => $request->payment,
                    'cart' => $cartItems,
                    'total' => $total
                ];
                
                Session::put('pending_order', $pendingOrderData);
                
                // Lưu vào database để backup
                $pendingOrder = \App\Models\PendingOrder::create([
                    'session_id' => session()->getId(),
                    'user_id' => $userId,
                    'customer_name' => $request->name,
                    'customer_email' => $request->email,
                    'customer_phone' => $request->phone,
                    'customer_address' => $request->address,
                    'payment_method' => 'MOMO',
                    'total' => $total,
                    'cart_data' => $cartItems->toArray(),
                    'expires_at' => now()->addHours(8) // 8 giờ
                ]);
                
                // Debug: Log session data
                \Log::info('Pending order saved to session and database', [
                    'session_id' => session()->getId(),
                    'pending_order_id' => $pendingOrder->id,
                    'pending_order' => $pendingOrderData,
                    'session_data' => Session::all()
                ]);

                // Gọi API MoMo để lấy payment URL
                $response = Http::post('http://localhost:5001/payment', [
                    'amount' => $total
                ]);

                if ($response->successful() && isset($response['payUrl'])) {
                    return response()->json([
                        'success' => true,
                        'redirect' => $response['payUrl']
                    ]);
            } else {
                    throw new \Exception("Không thể kết nối với cổng thanh toán MoMo");
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function success($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('checkout.success', compact('order'));
    }

    private function processMomoSuccess()
    {
        // Debug: Log current session state
        \Log::info('Processing MoMo success - Session debug', [
            'session_id' => session()->getId(),
            'session_data' => Session::all(),
            'pending_order_exists' => Session::has('pending_order')
        ]);

        // Get pending order from session
        $pendingOrder = Session::get('pending_order');
        
        // Nếu không có trong session, thử lấy từ database
        if (!$pendingOrder) {
            \Log::info('Pending order not found in session, trying database...');
            
            $dbPendingOrder = \App\Models\PendingOrder::where('session_id', session()->getId())
                ->where('expires_at', '>', now())
                ->first();
                
            if ($dbPendingOrder) {
                \Log::info('Found pending order in database', [
                    'pending_order_id' => $dbPendingOrder->id,
                    'db_data' => $dbPendingOrder->toArray()
                ]);
                
                // Chuyển đổi từ database format sang session format
                $pendingOrder = [
                    'user_id' => $dbPendingOrder->user_id,
                    'name' => $dbPendingOrder->customer_name,
                    'email' => $dbPendingOrder->customer_email,
                    'phone' => $dbPendingOrder->customer_phone,
                    'address' => $dbPendingOrder->customer_address,
                    'payment' => $dbPendingOrder->payment_method,
                    'cart' => collect($dbPendingOrder->cart_data)->map(function($item) {
                        return (object) $item; // Convert back to object for compatibility
                    }),
                    'total' => $dbPendingOrder->total
                ];
                
                // Cập nhật lại session
                Session::put('pending_order', $pendingOrder);
                
                \Log::info('Restored pending order from database to session');
            } else {
                \Log::error('Pending order not found in session or database', [
                    'session_id' => session()->getId(),
                    'available_session_keys' => array_keys(Session::all()),
                    'db_pending_orders' => \App\Models\PendingOrder::where('session_id', session()->getId())->get()
                ]);
                throw new \Exception('Không tìm thấy thông tin đơn hàng');
            }
        }

        \Log::info('Found pending order in session', [
            'pending_order' => $pendingOrder
        ]);

        DB::beginTransaction();

        try {
            // Create order
            $order = Order::create([
                'user_id' => $pendingOrder['user_id'],
                'order_number' => 'ORD-' . time(),
                'customer_name' => $pendingOrder['name'],
                'customer_email' => $pendingOrder['email'],
                'customer_phone' => $pendingOrder['phone'],
                'customer_address' => $pendingOrder['address'],
                'payment_method' => 'MOMO',
                'subtotal' => $pendingOrder['total'],
                'shipping_fee' => 0,
                'tax' => 0,
                'total' => $pendingOrder['total'],
                'notes' => '',
                'status' => 'pending'
            ]);

            \Log::info('Order created successfully', ['order_id' => $order->id]);

            // Create order details
            foreach ($pendingOrder['cart'] as $item) {
                // Get product info
                $product = $item->product ?? \App\Models\Product::find($item['product_id']);
                if (!$product) {
                    throw new \Exception("Không tìm thấy sản phẩm");
                }

                // Check product quantity
                if ($product->stock < $item->quantity) {
                    throw new \Exception("Sản phẩm {$product->name} không đủ số lượng");
                }

                // Create order detail
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item->quantity,
                    'price' => $product->price,
                    'total' => $product->price * $item->quantity
                ]);

                // Update product quantity
                $product->stock -= $item->quantity;
                $product->save();
            }

            DB::commit();

            \Log::info('Order processing completed successfully', [
                'order_id' => $order->id,
                'order_items_count' => count($pendingOrder['cart'])
            ]);

            // Clear cart and pending order
            Cart::where('user_id', $pendingOrder['user_id'])->delete();
            Session::forget('pending_order');
            
            // Xóa pending order khỏi database
            \App\Models\PendingOrder::where('session_id', session()->getId())->delete();

            \Log::info('Cart cleared and session cleaned', [
                'user_id' => $pendingOrder['user_id']
            ]);

            // Redirect to success page
            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Đặt hàng thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in processMomoSuccess', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e; // Re-throw exception để momoCallback xử lý
        }
    }

    public function momoCallback(Request $request)
    {
        // Log MoMo callback parameters
        \Log::info('MoMo callback received', [
            'request_data' => $request->all(),
            'session_id' => session()->getId(),
            'current_session_data' => Session::all()
        ]);
        
        // Kiểm tra nếu quay về từ MoMo thành công
        if ($request->resultCode === '0') {
            \Log::info('MoMo payment successful, processing order...');
            
            try {
                return $this->processMomoSuccess();
            } catch (\Exception $e) {
                \Log::error('Error processing MoMo success: ' . $e->getMessage(), [
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'session_id' => session()->getId(),
                    'session_data' => Session::all()
                ]);
                
                // Nếu có lỗi, hiển thị trang lỗi thay vì redirect về checkout
                return view('shop.momo-return', [
                    'resultCode' => '1',
                    'message' => 'Có lỗi xảy ra khi xử lý đơn hàng: ' . $e->getMessage()
                ]);
            }
        }
        
        \Log::info('MoMo payment failed or cancelled', [
            'resultCode' => $request->resultCode ?? 'unknown',
            'message' => $request->message ?? 'No message'
        ]);
        
        // Nếu thanh toán thất bại, hiển thị trang lỗi thay vì redirect về checkout
        return view('shop.momo-return', [
            'resultCode' => $request->resultCode ?? '1',
            'message' => $request->message ?? 'Thanh toán MoMo thất bại'
        ]);
    }
}

