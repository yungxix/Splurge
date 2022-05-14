<div class="flex flex-row justify-end bg-gray-200 py-4 px-2 items-center gap-x-12 border-t border-gray-400">
    <a class="link" href="{{ route('admin.services.edit', $service) }}">
        Edit
    </a>

    <a class="link" href="{{ route('admin.service_detail.tiers.index', $service) }}">
        Manage pricing tiers
    </a>

    <a class="link" href="{{ route('admin.services.create') }}">
        New service
    </a>
</div>
