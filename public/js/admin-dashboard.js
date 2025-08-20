// Admin Dashboard Charts
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin Dashboard JS loaded');
    
    // Orders Chart
    const ordersChartElement = document.getElementById('ordersChart');
    if (ordersChartElement) {
        const ordersCtx = ordersChartElement.getContext('2d');
        
        // Lấy dữ liệu từ data attributes hoặc từ global variable
        let monthlyOrdersData = [];
        let monthlyOrdersLabels = [];
        
        // Nếu có dữ liệu từ Laravel
        if (typeof window.monthlyOrdersData !== 'undefined') {
            monthlyOrdersData = window.monthlyOrdersData;
            monthlyOrdersLabels = window.monthlyOrdersLabels;
        }
        
        const ordersChart = new Chart(ordersCtx, {
            type: 'line',
            data: {
                labels: monthlyOrdersLabels,
                datasets: [{
                    label: 'Số đơn hàng',
                    data: monthlyOrdersData,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
        
        console.log('Orders chart created');
    } else {
        console.log('Orders chart element not found');
    }
    
    // Revenue Chart
    const revenueChartElement = document.getElementById('revenueChart');
    if (revenueChartElement) {
        const revenueCtx = revenueChartElement.getContext('2d');
        
        let monthlyRevenueData = [];
        let monthlyRevenueLabels = [];
        
        if (typeof window.monthlyRevenueData !== 'undefined') {
            monthlyRevenueData = window.monthlyRevenueData;
            monthlyRevenueLabels = window.monthlyRevenueLabels;
        }
        
        const revenueChart = new Chart(revenueCtx, {
            type: 'doughnut',
            data: {
                labels: monthlyRevenueLabels,
                datasets: [{
                    data: monthlyRevenueData,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
        console.log('Revenue chart created');
    } else {
        console.log('Revenue chart element not found');
    }
});
