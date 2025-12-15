<div class="filter-bar mb-3 d-flex justify-content-between">

    <form id="filterForm" action="{{ route('dokumen.laporan') }}" method="GET" class="d-flex gap-2">

        {{-- Search Judul --}}
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}"
            placeholder="Cari judul laporan..." 
            class="form-control"
            style="width: 250px"
            oninput="autoSubmit()"
        >

        {{-- Filter Status --}}
        <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit()">
            <option value="">Semua Status</option>
            <option value="draft" {{ request('status')=='draft' ? 'selected':'' }}>Draft</option>
            <option value="published" {{ request('status')=='published' ? 'selected':'' }}>Published</option>
            <option value="archived" {{ request('status')=='archived' ? 'selected':'' }}>Archived</option>
        </select>

        {{-- Filter Pembuat --}}
        <select name="owner" class="form-select" onchange="document.getElementById('filterForm').submit()">
            <option value="">Semua Pembuat </option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ request('owner')==$u->id ? 'selected':'' }}>
                    {{ $u->name }}
                </option>
            @endforeach
        </select>

    </form>

    {{-- Tombol Reset --}}
    <a href="{{ route('dokumen.laporan') }}" class="btn btn-secondary">
        Reset
    </a>
</div>

<script>
    let timer;
    function autoSubmit() {
        clearTimeout(timer);
        timer = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500); // Submit setelah 500ms tidak ada input baru
    }
</script>
