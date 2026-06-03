<div class="flex items-center gap-2">
    @if($canEdit)
    <a href="{{ $editRoute }}" class="btn btn-warning btn-sm">Edit</a>
    @endif

    @if($canDelete)
    <form id="{{ $deleteFormId }}" method="POST" action="{{ $deleteRoute }}">
        @csrf @method('DELETE')
    </form>
    <button onclick="confirmDelete('{{ $deleteFormId }}')" class="btn btn-danger btn-sm">Hapus</button>
    @endif
</div>
