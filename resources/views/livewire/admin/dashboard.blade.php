<div>
    {{-- Page Header --}}
    <x-admin.page-header title="Dashboard Overview" subtitle="Welcome back! Here's what's happening today.">
        <x-slot:actions>
            <x-admin.button variant="primary" icon="fas fa-plus">New Report</x-admin.button>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <x-admin.stat-card icon="fas fa-dollar-sign" label="Total Revenue" value="$48,574"
                trend-value="12.5% from last month" trend-direction="up" variant="primary" />
        </div>
        <div class="col-md-6 col-lg-3">
            <x-admin.stat-card icon="fas fa-shopping-bag" label="New Orders" value="1,245"
                trend-value="8.2% from last month" trend-direction="up" variant="secondary" />
        </div>
        <div class="col-md-6 col-lg-3">
            <x-admin.stat-card icon="fas fa-users" label="Total Users" value="8,456" trend-value="15.3% from last month"
                trend-direction="up" variant="success" />
        </div>
        <div class="col-md-6 col-lg-3">
            <x-admin.stat-card icon="fas fa-chart-pie" label="Conversion Rate" value="3.24%"
                trend-value="2.1% from last month" trend-direction="down" variant="warning" />
        </div>
    </div>

    {{-- Recent Orders Table --}}
    <x-admin.table-card title="Recent Orders" view-all-href="#orders" :headers="['Order ID', 'Customer', 'Product', 'Amount', 'Status', 'Date']">
        @foreach ($orders as $order)
            <tr>
                <td><strong style="color: var(--text-primary);">{{ $order->order_id }}</strong></td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->product_name }}</td>
                <td><strong style="color: var(--text-primary);">{{ $order->amount }}</strong></td>
                <td><x-admin.badge :variant="$order->status_variant"
                        :icon="$order->status_icon">{{ $order->status }}</x-admin.badge></td>
                <td class="text-muted">{{ $order->created_at->format('M d, Y') }}</td>
            </tr>
        @endforeach
    </x-admin.table-card>
</div>