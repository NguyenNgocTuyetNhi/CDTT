<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->orderBy('created_at', 'desc')->paginate(10);
        return view('shop.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Auth::user()->orders()->with('items.product')->findOrFail($id);
        return view('shop.orders.show', compact('order'));
    }
    
    public function momoReturn(Request $request)
    {
        // Hiển thị trang loading trước
        if (!$request->has('resultCode')) {
            return view('shop.momo-return');
        }

        try {
            // Get callback data from MoMo
            $resultCode = $request->resultCode;
            $message = $request->message;

            // Get pending order from session
            $pendingOrder = Session::get('pending_order');
            if (!$pendingOrder) {
                return redirect()->route('shop.checkout')
                    ->with('error', 'Không tìm thấy thông tin đơn hàng');
            }

            // Check payment result
            if ($resultCode === '0') {
                DB::beginTransaction();

                // Create order
                $order = Order::create([
                    'user_id' => $pendingOrder['user_id'],
                    'name' => $pendingOrder['name'],
                    'email' => $pendingOrder['email'],
                    'phone' => $pendingOrder['phone'],
                    'address' => $pendingOrder['address'],
                    'created_by' => $pendingOrder['user_id'],
                    'updated_by' => $pendingOrder['user_id'],
                    'status' => 1,
                    'payment' => 'MOMO'
                ]);

                // Create order details
                foreach ($pendingOrder['cart'] as $item) {
                    // Get product info
                    $product = Product::find($item['id']);
                    if (!$product) {
                        throw new \Exception("Không tìm thấy sản phẩm");
                    }

                    // Check product quantity
                    if ($product->qty < $item['qty']) {
                        throw new \Exception("Sản phẩm {$item['name']} không đủ số lượng");
                    }

                    // Create order detail
                    $orderDetail = new OrderDetail();
                    $orderDetail->order_id = $order->id;
                    $orderDetail->product_id = $product->id;
                    $orderDetail->price = $item['price_buy'];
                    $orderDetail->qty = $item['qty'];
                    $orderDetail->amount = $item['price_buy'] * $item['qty'];
                    $orderDetail->discount = 0;
                    $orderDetail->thumbnail = $product->thumbnail;
                    $orderDetail->save();

                    // Update product quantity
                    $product->qty -= $item['qty'];
                    $product->save();
                }

                DB::commit();

                // Clear cart and pending order
                Session::forget(['cart', 'pending_order']);

                return redirect()->route('shop.thanks')
                    ->with('success', 'Đặt hàng thành công');
            } else {
                // Payment failed
                return redirect()->route('shop.checkout')
                    ->with('error', 'Thanh toán thất bại: ' . $message);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('shop.checkout')
                ->with('error', $e->getMessage());
        }
    }
    public function placeOrder(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'user_id' => 'required|numeric',
            'payment' => 'required|in:COD,MOMO'
        ], [
            'name.required' => 'Vui lòng nhập họ tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'address.required' => 'Vui lòng nhập địa chỉ giao hàng',
            'user_id.required' => 'Vui lòng đăng nhập để đặt hàng',
            'payment.required' => 'Vui lòng chọn phương thức thanh toán',
            'payment.in' => 'Phương thức thanh toán không hợp lệ'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng kiểm tra lại thông tin',
                'errors' => $validator->errors()
            ], 422);
        }

        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng của bạn đang trống'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Tạo đơn hàng
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price_buy'] * $item['qty'];
            }

            // Xử lý theo phương thức thanh toán
            if ($request->payment === 'COD') {
                // Tạo đơn hàng COD
                $order = Order::create([
                    'user_id' => $request->user_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'created_by' => $request->user_id,
                    'updated_by' => $request->user_id,
                    'status' => 1,
                    'payment' => 'COD'
                ]);

                // Tạo chi tiết đơn hàng
                foreach ($cart as $item) {
                    // Lấy thông tin sản phẩm
                    $product = Product::find($item['id']);
                    if (!$product) {
                        throw new \Exception("Không tìm thấy sản phẩm");
                    }

                    // Kiểm tra số lượng tồn kho
                    if ($product->qty < $item['qty']) {
                        throw new \Exception("Sản phẩm {$item['name']} không đủ số lượng");
                    }

                    // Tạo chi tiết đơn hàng
                    $orderDetail = new OrderDetail();
                    $orderDetail->order_id = $order->id;
                    $orderDetail->product_id = $product->id;
                    $orderDetail->price = $item['price_buy'];
                    $orderDetail->qty = $item['qty'];
                    $orderDetail->amount = $item['price_buy'] * $item['qty'];
                    $orderDetail->discount = 0;
                    $orderDetail->thumbnail = $product->thumbnail;
                    $orderDetail->save();

                    // Cập nhật số lượng sản phẩm
                    $product->qty -= $item['qty'];
                    $product->save();
                }

                DB::commit();
                Session::forget('cart');

                return response()->json([
                    'success' => true,
                    'message' => 'Đặt hàng thành công',
                    'redirect' => route('shop.thanks')
                ]);

            } elseif ($request->payment === 'MOMO') {
                // Lưu thông tin đơn hàng tạm thời vào session
                Session::put('pending_order', [
                    'user_id' => $request->user_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'payment' => $request->payment,
                    'cart' => $cart,
                    'total' => $total
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

}
