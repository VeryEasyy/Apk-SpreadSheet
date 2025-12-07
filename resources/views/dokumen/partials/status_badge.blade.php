@if ($status == 'published')
    <span class="status-badge status-published">Published</span>
@elseif ($status == 'archived')
    <span class="status-badge status-archived">Archived</span>
@else
    <span class="status-badge status-draft">Draft</span>
@endif