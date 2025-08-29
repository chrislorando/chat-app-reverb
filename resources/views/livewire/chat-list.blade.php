<div>
    <aside wire:ignore.self
    class="fixed top-0 left-0 z-40 w-screen md:w-96 h-screen transition-transform -translate-x-full bg-white border-r border-gray-200 md:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    tabindex="-1" 
    aria-labelledby="Sidenav"
    id="drawer-navigation"
>
        <div class="overflow-y-auto py-2  h-full bg-white dark:bg-gray-800">
        
            <div class="fixed top-0 bg-gray-800 p-2 w-full">
                <div class="flex items-center p-2 mb-2">
                    <a href="https://github.com/chrislorando/chat-reverb-app" class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">{{ config('app.name', 'Laravel') }}</a>

                    <div class="ml-auto flex items-center gap-2">
                        <button type="button" @click="$dispatch('toggle-contact')" data-drawer-target="drawer-contact" data-drawer-show="drawer-contact" data-drawer-placement="left" data-drawer-backdrop="false" data-drawer-body-scrolling="true" aria-controls="drawer-contact" class="px-2 py-2 text-sm font-medium text-center inline-flex items-center text-white rounded-full hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 8H4m8 3.5v5M9.5 14h5M4 6v13a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1h-5.032a1 1 0 0 1-.768-.36l-1.9-2.28a1 1 0 0 0-.768-.36H5a1 1 0 0 0-1 1Z"/>
                            </svg>
                        </button>

                        <button id="userMenuButton" data-dropdown-toggle="userMenu" data-dropdown-placement="bottom-start" class="px-2 py-2 text-sm font-medium text-center inline-flex items-center text-white rounded-full hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"></path>
                            </svg>
                        </button>

                        <div id="userMenu" class="z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-40 dark:bg-gray-600 dark:divide-gray-600">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="userMenuButton">
                                <li>
                                    <a wire:navigate href="{{ route('profile') }}"  class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{ __('Profile') }}</a>
                                </li>
                                <li>
                                    <a wire:click='logout' class="cursor-pointer block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{ __('Log Out') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <form action="#" method="GET" class="mb-4">
                    <label for="sidebar-search" class="sr-only">Search</label>
                    <div class="relative">
                        <div
                        class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none"
                        >
                        <svg
                            class="w-5 h-5 text-gray-500 dark:text-gray-400"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            ></path>
                        </svg>
                        </div>
                        <input
                            wire:model.live='search'
                            type="text"
                            name="search"
                            id="sidebar-search"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Search"
                        />
                    </div>
                </form>

            
                <button type="button" wire:click.prevent="$set('filter', null)" class=" text-green-800 text-sm font-medium me-2 px-3 py-1 rounded-full dark:text-green-300 border border-green-400 {{ $filter==null ? 'dark:bg-green-900 bg-green-100' : '' }}">All</button>

                <button type="button" wire:click.prevent="$set('filter', 'Unread')" class=" text-green-800 text-sm font-medium me-2 px-3 py-1 rounded-full dark:text-green-300 border border-green-400 {{ $filter=='Unread' ? 'dark:bg-green-900 bg-green-100' : '' }}">Unread</button>


            </div>

            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700 space-y-0 mt-40">
                @php($i=0)
                @foreach($models as $row)
                @php($i++)
                    <li wire:key='{{ $row->id }}' class="flex items-start justify-between  group text-gray-900  dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group py-3 sm:py-4 {{ $isActiveChat==$row->id ? 'bg-slate-700' : '' }} px-3 cursor-pointer">
                        <a 
                        class="flex items-center flex-1" 
                        data-drawer-target="drawer-navigation"
                        data-drawer-hide="drawer-navigation"
                        data-drawer-backdrop="false"
                        data-drawer-body-scrolling="true"
                        aria-controls="drawer-navigation"
                        wire:click='chat({{ $row->id }})'
                        >
                            <div class="flex-shrink-0">
                                {{-- <img class="w-8 h-8 rounded-full" src="https://avatar.iran.liara.run/public/{{$row->id < 100 ? $row->id : 100 }}" alt="Neil image"> --}}
                                {{-- <img class="w-8 h-8 rounded-full" src="https://avatar.iran.liara.run/public/1" alt=" {{ $row->name }}"> --}}
                               
                                <x-avatar :avatar="$row->avatar" :avatar_initials="$row->avatarName['initials']" :avatar_color="$row->avatarName['color']" />

                            </div>
                            <div class="flex-1 min-w-0 ms-4">
                                @php($content = $row->latest_message?->content)

                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                    {{ $row->alias_name ?? $row->email ?? $row->name }} 
                                </p>

                                <p class="text-sm text-gray-500 truncate dark:text-gray-400 flex" title="{{$content == '' ? $row->latest_message?->message_type : $content}}">
                                    @if($row->latest_message?->file_url)
                                        @if($row->latest_message?->message_type == 'Document')
                                            <svg class="w-[18px] h-[18px] text-gray-800 dark:text-white me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m14-4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z"/>
                                            </svg>

                                        @endif

                                        @if($row->latest_message?->message_type == 'Image')
                                            <svg class="w-[18px] h-[18px] text-gray-800 dark:text-white me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M16 18H8l2.5-6 2 4 1.5-2 2 4Zm-1-8.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Z"/>
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m14-4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1ZM8 18h8l-2-4-1.5 2-2-4L8 18Zm7-8.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Z"/>
                                            </svg>
                                        @endif

                                    @endif

                                    {{-- {!! preg_replace_callback(
                                        '/(https?:\/\/[^\s]+)/',
                                        fn($match) => '<svg class="w-[18px] h-[18px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.213 9.787a3.391 3.391 0 0 0-4.795 0l-3.425 3.426a3.39 3.39 0 0 0 4.795 4.794l.321-.304m-.321-4.49a3.39 3.39 0 0 0 4.795 0l3.424-3.426a3.39 3.39 0 0 0-4.794-4.795l-1.028.961"/></svg>',
                                        nl2br(e($row->latest_message?->content))
                                    ) !!}
                                    
                                    {{ $row->latest_message?->content 
                                        ? \Illuminate\Support\Str::limit($row->latest_message->content, 35) 
                                        : $row->latest_message->file_name ?? $row->email }} --}}

                                    @if ($content)
                                        {{ \Illuminate\Support\Str::limit($content, 35) }}
                                    @else
                                        {{ $row->latest_message?->file_name ?? $row->email }}
                                    @endif

                                </p>
                            </div>
                            {{-- <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white {{ $row->unread_messages_count <=0 ? 'hidden' : '' }}">
                                <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">{{$row->unread_messages_count}}</span>
                            </div> --}}
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                <livewire:chat-unread-badge :senderId="$row->id" :authId="auth()->id()" :wire:key="'badge-'.$row->id" />
                            </div>
                        </a>

                        <div class="flex flex-col items-end justify-between">
                            <span class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                @if($row->latest_message?->created_at?->isToday())
                                    {{ $row->latest_message?->created_at->format('H:i') }}
                                @else
                                    {{ $row->latest_message?->created_at->format('M j, Y') }}
                                @endif
                            </span>

                            <div class="flex items-center space-x-2">
                                @if($row->contact?->is_pinned)
                                    <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v4.997a.31.31 0 0 1-.068.113c-.08.098-.213.207-.378.301-.947.543-1.713 1.54-2.191 2.488A6.237 6.237 0 0 0 4.82 14.4c-.1.48-.138 1.031.018 1.539C5.12 16.846 6.02 17 6.414 17H11v3a1 1 0 1 0 2 0v-3h4.586c.395 0 1.295-.154 1.575-1.061.156-.508.118-1.059.017-1.539a6.241 6.241 0 0 0-.541-1.5c-.479-.95-1.244-1.946-2.191-2.489a1.393 1.393 0 0 1-.378-.301.309.309 0 0 1-.068-.113V5h1a1 1 0 1 0 0-2H7a1 1 0 1 0 0 2h1Z"/>
                                    </svg>
                                @endif

                                <button id="dropdown-chat-list-button-{{ $row->id }}" 
                                    data-dropdown-toggle="dropdown-chat-list-{{ $row->id }}" 
                                    class="md:hidden bold group-hover:inline-flex items-center justify-center text-sm font-bold text-gray-800 dark:text-gray-300"
                                >
                                    <svg class="w-5 h-5 text-gray-800 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M12 6h.01M12 12h.01M12 18h.01"/>
                                    </svg>
                                </button>
                            </div>
                        </div>


                        <div id="dropdown-chat-list-{{$row->id}}" data-dropdown-placement="right" class="z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-44 border-2 border-gray-600 dark:bg-gray-700">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                                <li>
                                    @if(!$row->contact?->is_pinned)
                                        <button wire:click="pinChat({{$row->id}})" type="button" class="flex w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <svg class="w-5 h-5 me-2 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v4.997a.31.31 0 0 1-.068.113c-.08.098-.213.207-.378.301-.947.543-1.713 1.54-2.191 2.488A6.237 6.237 0 0 0 4.82 14.4c-.1.48-.138 1.031.018 1.539C5.12 16.846 6.02 17 6.414 17H11v3a1 1 0 1 0 2 0v-3h4.586c.395 0 1.295-.154 1.575-1.061.156-.508.118-1.059.017-1.539a6.241 6.241 0 0 0-.541-1.5c-.479-.95-1.244-1.946-2.191-2.489a1.393 1.393 0 0 1-.378-.301.309.309 0 0 1-.068-.113V5h1a1 1 0 1 0 0-2H7a1 1 0 1 0 0 2h1Z"/>
                                            </svg>
                                            Pin chat
                                        </button>
                                    @else
                                        <button wire:click="unpinChat({{$row->id}})" type="button" class="w-full text-left flex px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <svg class="w-5 h-5 me-2 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M12.0001 20v-4M7.00012 4h9.99998M9.00012 5v5c0 .5523-.46939 1.0045-.94861 1.279-1.43433.8217-2.60135 3.245-2.25635 4.3653.07806.2535.35396.3557.61917.3557H17.5859c.2652 0 .5411-.1022.6192-.3557.3449-1.1204-.8221-3.5436-2.2564-4.3653-.4792-.2745-.9486-.7267-.9486-1.279V5c0-.55228-.4477-1-1-1h-4c-.55226 0-.99998.44772-.99998 1Z"/>
                                            </svg>
                                            Unpin chat
                                        </button>
                                    @endif
                                </li>
                                <li>
                                    <button wire:click="markAsRead({{$row->id}})" type="button" class="w-full text-left flex px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                        <svg class="w-5 h-5 me-2 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 9h5m3 0h2M7 12h2m3 0h5M5 5h14a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1h-6.616a1 1 0 0 0-.67.257l-2.88 2.592A.5.5 0 0 1 8 18.477V17a1 1 0 0 0-1-1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>
                                        </svg>
                                        Mark as read
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endforeach
                
            </ul>

            @if($models->hasMorePages())
                <div x-intersect="$wire.loadMore()"></div>
            @endif
        </div>

    </aside>

    <div id="drawer-contact" class="w-screen md:w-96 fixed top-0 left-0 z-40 h-screen p-4 overflow-y-auto transition-transform -translate-x-full dark:bg-gray-800 bg-white border-r border-gray-200 dark:border-gray-700 {{ $isAddContactOpen ? 'transform-none' : '' }}" tabindex="-1" aria-labelledby="drawer-contact-label">
        <h5 id="drawer-contact-label" class="inline-flex items-center mb-6 text-base font-semibold text-gray-500 uppercase dark:text-gray-400">
            <svg class="w-6 h-6 me-2.5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
            </svg>
            New contact
        </h5>

        <button type="button" @click="$dispatch('toggle-contact')" data-drawer-hide="drawer-contact" aria-controls="drawer-contact" class="mb-2 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white" >
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close menu</span>
        </button>

        <livewire:chat-contact-form :key="'chat-contact-form-1'" :isNewRecord="true" />

    </div>
</div>
{{-- @script
<script>
    const userId = window.Laravel.userId;
    console.log('Receiver ID:', userId);

    Echo.private(`chatlist.${userId}`)
    .listen('MessageSent', (e) => {
        console.log('MessageSent event received:', e);
        Livewire.dispatch('echo-private:chatlist.' + userId + ',MessageSent', e);
    });
    
   

   
</script>
@endscript --}}