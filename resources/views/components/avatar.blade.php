@props(['avatar' => null, 'avatar_color' => null, 'avatar_initials' => null, 'size' => 'sm'])
<div>
    @if($avatar==null)
        <div class="{{ $size=='sm' ? 'w-10 h-10' : 'w-24 h-24' }} rounded-full flex items-center justify-center {{ $avatar_color }}">
            <span class="{{ $size=='sm' ? 'text-xs' : 'text-xl' }} font-medium text-white">
                {{ $avatar_initials }} 
            </span>
        </div>
    @else
        <div class="{{ $size=='sm' ? 'w-10 h-10' : 'w-24 h-24' }} rounded-full flex items-center justify-center border border-gray-600 shadow-sm">
            <img src="{{ $avatar }}" class="rounded-full object-cover " alt="" />
        </div>
    @endif
</div>