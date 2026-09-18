    <i class="fas ml-1 text-[9px] 
    @if ($sortField !== $field) fa-sort text-gray-300 
    @elseif($sortDir === 'asc') fa-sort-up text-indigo-600 
    @else fa-sort-down text-indigo-600 @endif"
        aria-hidden="true"></i>
