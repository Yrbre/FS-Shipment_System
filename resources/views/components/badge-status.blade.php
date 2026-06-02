@props(['status'])
<span
    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
    {{ match (strtolower($status)) {
        'pending' => 'bg-gray-100 text-gray-700',
        'in transit' => 'bg-blue-100 text-blue-700',
        'arrived' => 'bg-yellow-100 text-yellow-700',
        'verified' => 'bg-green-100 text-green-700',
        'stored' => 'bg-emerald-100 text-emerald-700',
        'rejected' => 'bg-red-100 text-red-700',
        default => 'bg-gray-100 text-gray-600',
    } }}">
    {{ $status }}
</span>
