<div>
    <header class="bg-white dark:bg-gray-800 shadow fixed left-0 md:left-96 right-0 md:z-40 z-20">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-4">
            <div class="flex items-center">
                <button 
                    data-drawer-target="drawer-navigation"
                    data-drawer-show="drawer-navigation"
                    data-drawer-backdrop="false"
                    data-drawer-body-scrolling="true"
                    aria-controls="drawer-navigation"
                    class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer md:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                    >
                    <svg
                        aria-hidden="true"
                        class="w-6 h-6"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                        fill-rule="evenodd"
                        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                        clip-rule="evenodd"
                        ></path>
                    </svg>
                    <svg
                        aria-hidden="true"
                        class="hidden w-6 h-6"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                        fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                        ></path>
                    </svg>
                    <span class="sr-only">Toggle sidebar</span>
                </button>

                <div class="flex-shrink-0">
                    <div class="relative">
                        {{-- <img class="w-10 h-10 rounded-full" src="https://avatars.githubusercontent.com/u/167683279?v=4" alt=""> --}}
                        <div class="w-8 h-8 rounded-full {{ $userModel->avatar['color'] }}  flex items-center justify-center">
                            <span class="text-xs font-medium text-white">
                                {{ $userModel->avatar['initials'] }}
                            </span>
                        </div>
                        <span class="bottom-0 left-7 absolute  w-3.5 h-3.5 {{ $isOnline ? 'animate-pulse bg-green-400' : 'bg-red-400' }} border-2 border-white dark:border-gray-800 rounded-full"></span>
                        {{-- <livewire:chat-user-online :senderId="$senderId" :authId="$authId" :wire:key="'chat-online-'.$senderId.$authId" /> --}}
                    </div>
                    {{-- <img class="w-8 h-8 rounded-full" src="https://avatars.githubusercontent.com/u/167683279?v=4" alt="Neil image"> --}}
                </div>
                <div class="flex-1 min-w-0 ms-4">
                    <button type="button" @click="$dispatch('toggle-profile')" data-drawer-target="drawer-profile" data-drawer-show="drawer-profile" data-drawer-placement="right" data-drawer-backdrop="false" data-drawer-body-scrolling="true" aria-controls="drawer-profile" class="text-sm font-medium text-gray-900 truncate dark:text-white">
                        {{ $userModel->alias_name ?? $userModel->email }} 
                    </button>
                
                    @if ($isTyping)
                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                            typing...
                        </p>
                    {{-- @else
                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                            {{ $userModel->email }}
                        </p> --}}
                    @endif
                </div>
            </div>
        </div>
    </header>

    <div id="drawer-profile" class="fixed top-0 right-0 z-50 w-screen md:w-96 h-screen p-4 overflow-y-auto transition-transform translate-x-full bg-white dark:bg-gray-800 border-s border-slate-600 {{ $isProfileOpen ? 'transform-none' : '' }}" tabindex="-1" aria-labelledby="drawer-profile-label">
        <button type="button" @click="$dispatch('toggle-contact-header', [{{ $userModel->id }}])" id="drawer-profile-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400">
            <svg class="w-6 h-6 me-2.5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28"/>
            </svg>
            Contact info
        </button>
        <button type="button" @click="$dispatch('toggle-profile')" data-drawer-hide="drawer-profile" aria-controls="drawer-profile" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white" >
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close menu</span>
        </button>
        <div class="flex flex-col items-center pb-10">
            {{-- <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="/docs/images/people/profile-picture-3.jpg" alt="Bonnie image"/> --}}
            <div class="w-24 h-24 mb-3 shadow-lg rounded-full {{ $userModel->avatar['color'] }}  flex items-center justify-center">
                <span class="text-xl font-medium text-white">
                    {{ $userModel->avatar['initials'] }}
                </span>
            </div>
            <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $userModel->alias_name ?? $userModel->name }} </h5>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $userModel->email }}</span>
        </div>

        <h6 class="mb-1 text-sm text-gray-900 md:text-md dark:text-white">
            About
        </h6>
        <p class="text-sm font-normal text-gray-500 dark:text-gray-400">{{ $userModel->about }}</p>

        <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

        <button type="button" @click="$dispatch('toggle-media');$dispatch('show-media')" class="flex items-center gap-2 mb-3" data-drawer-target="drawer-media" data-drawer-show="drawer-media" data-drawer-placement="right" data-drawer-backdrop="false" data-drawer-body-scrolling="true" aria-controls="drawer-media">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3 16 5-7 6 6.5m6.5 2.5L16 13l-4.286 6M14 10h.01M4 19h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
            </svg>
            <h6 class="text-sm text-gray-900 md:text-md dark:text-white">Media, links and docs</h6>
        </button>
        <div class="grid grid-cols-4 gap-4">
            @foreach($thumbnails as $m)
                <div class="h-20 flex items-center justify-center bg-gray-500/20 rounded-lg overflow-hidden">
                    <img class="max-h-full max-w-full object-contain" src="{{ $m->file_url }}" alt="">
                </div>
            @endforeach
        </div>

        <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

        <ul class="w-full space-y-2 text-gray-500 list- dark:text-gray-400">
            <li class="flex items-center">
                <a class="w-full flex items-center hover:bg-gray-700 p-4 ps-2.5 rounded-lg text-red-500">
                    <svg class="w-6 h-6 me-3 text-red-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m6 6 12 12m3-6a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    Block {{ $userModel->name }}
                </a>
            </li>
            <li class="flex items-center">
                <a class="w-full flex items-center hover:bg-gray-700 p-4 ps-2.5 rounded-lg text-red-500">
                    <svg class="w-6 h-6 me-3 text-red-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13c-.889.086-1.416.543-2.156 1.057a22.322 22.322 0 0 0-3.958 5.084 1.6 1.6 0 0 1-.582.628 1.549 1.549 0 0 1-1.466.087 1.587 1.587 0 0 1-.537-.406 1.666 1.666 0 0 1-.384-1.279l1.389-4.114M17 13h3V6.5A1.5 1.5 0 0 0 18.5 5v0A1.5 1.5 0 0 0 17 6.5V13Zm-6.5 1H5.585c-.286 0-.372-.014-.626-.15a1.797 1.797 0 0 1-.637-.572 1.873 1.873 0 0 1-.215-1.673l2.098-6.4C6.462 4.48 6.632 4 7.88 4c2.302 0 4.79.943 6.67 1.475"/>
                    </svg>
                    Report {{ $userModel->name }}
                </a>
            </li>
            <li class="flex items-center">
                <a class="w-full flex items-center hover:bg-gray-700 p-4 ps-2.5 rounded-lg text-red-500">
                    <svg class="w-6 h-6 me-3 text-red-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                    </svg>
                    Delete Chat
                </a>
            </li>
        </ul>
    </div>

    <div id="drawer-media" class="fixed top-0 right-0 z-50 w-screen md:w-96 h-screen p-4 overflow-y-auto transition-transform translate-x-full bg-white dark:bg-gray-800 border-s border-slate-600 {{ $isMediaOpen ? 'transform-none' : '' }}" tabindex="-1" aria-labelledby="drawer-media-label">
        
        <button type="button" @click="$dispatch('toggle-media')" data-drawer-hide="drawer-media" aria-controls="drawer-media" class="mb-2 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white" >
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close menu</span>
        </button>
       
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul wire:ignore.self class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" data-tabs-active-classes="text-green-600 hover:text-green-600 dark:text-green-500 dark:hover:text-green-500 border-green-600 dark:border-green-500" data-tabs-inactive-classes="dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300" role="tablist">
                <li class="me-2" role="presentation">
                    <button type="button" wire:click.prevent="$dispatch('show-media')" class="inline-block p-4 border-b-2 rounded-t-lg {{ $isTabMediaOpen ? 'text-green-600 hover:text-green-600 dark:text-green-500 dark:hover:text-green-500 border-green-600 dark:border-green-500' : 'dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300' }}" id="media-tab" data-tabs-target="#media" type="button" role="tab" aria-controls="media" aria-selected="{{ $isTabMediaOpen }}">Media</button>
                </li>
                <li class="me-2" role="presentation">
                    <button type="button" wire:click.prevent="$dispatch('show-docs')" class="inline-block p-4 border-b-2 rounded-t-lg {{ $isTabDocsOpen ? 'text-green-600 hover:text-green-600 dark:text-green-500 dark:hover:text-green-500 border-green-600 dark:border-green-500' : 'dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300' }}" id="docs-tab" data-tabs-target="#docs" type="button" role="tab" aria-controls="docs" aria-selected="{{ $isTabDocsOpen }}">Docs</button>
                </li>
                <li class="me-2" role="presentation">
                    <button type="button" wire:click.prevent="$dispatch('show-links')" class="inline-block p-4 border-b-2 rounded-t-lg {{ $isTabLinksOpen ? 'text-green-600 hover:text-green-600 dark:text-green-500 dark:hover:text-green-500 border-green-600 dark:border-green-500' : 'dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300' }}" id="links-tab" data-tabs-target="#links" type="button" role="tab" aria-controls="links" aria-selected="{{ $isTabLinksOpen }}">Links</button>
                </li>
              
            </ul>
        </div>
        <div id="default-tab-content">
            <div class="{{ $isTabMediaOpen ? '' : 'hidden' }} p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="media" role="tabpanel" aria-labelledby="media-tab">
                @if($media)
                    @foreach ($media as $monthYear => $images)
                        <p class="text-sm text-gray-900 dark:text-white uppercase mb-2">{{ $monthYear }}</p>
                        {{-- <div class="grid grid-cols-4 gap-4">
                            @foreach ($images as $image)
                                <img class="h-full w-full object-cover rounded-lg" src="{{$image->file_url}}" alt="">
                            @endforeach
                        </div> --}}

                        <div class="grid grid-cols-4 gap-4">
                            @foreach ($images as $image)
                                <div class="h-20 flex items-center justify-center bg-gray-500/20 rounded-lg overflow-hidden">
                                    <img class="max-h-full max-w-full object-contain" src="{{$image->file_url}}" alt="">
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                   
                @endif
            </div>
            <div class="{{ $isTabDocsOpen ? '' : 'hidden' }} p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="docs" role="tabpanel" aria-labelledby="docs-tab">
                @if($docs)
                    @foreach ($docs as $row)
                        <div class="flex items-start gap-4 mb-4">
                            <div class="flex flex-col gap-2.5">
                                <div class="flex flex-col w-full max-w-[326px] leading-1.5 p-4  {{ $row->sender_id==auth()->id() ? 'rounded-s-xl rounded-br-xl border-green-200 bg-green-100 dark:bg-green-700' : 'rounded-e-xl rounded-es-xl border-gray-200 bg-gray-100 dark:bg-gray-700' }}">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <span class="text-xs font-normal text-gray-500 dark:text-gray-300">
                                            @if (\Carbon\Carbon::parse($row->timestamp)->isToday())
                                                {{ \Carbon\Carbon::parse($row->timestamp)->format('H:i') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($row->timestamp)->format('d M Y H:i') }}
                                            @endif
                                        </span>
                                    </div>
                                    @if($row->message_type == 'Image')
                            
                                        <div class="group relative my-2.5">
                                            <div class="absolute w-full h-full bg-gray-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg flex items-center justify-center">
                                                <a href="{{ $row->fileUrl() }}" download="{{ $row->file_name }}" data-tooltip-target="download-image" class="inline-flex items-center justify-center rounded-full h-10 w-10 bg-white/30 hover:bg-white/50 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50">
                                                    <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 18">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 1v11m0 0 4-4m-4 4L4 8m11 4v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3"/>
                                                    </svg>
                                                </a>
                                                <div id="download-image" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                                                    Download image
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                            </div>
                                            <img src="{{ $row->fileUrl() }}" class="rounded-lg" />
                                        </div>
                                    @elseif($row->message_type == 'Document')
                                        <div class="break-all flex justify-between items-start my-2.5 bg-gray-50 {{ $row->sender_id==auth()->id() ? 'dark:bg-green-800' : 'dark:bg-gray-600' }} rounded-xl p-2 w-full">
                                            <div class="me-2">
                                                <span class="flex items-center gap-2 text-sm font-medium text-gray-900 dark:text-white pb-2">
                                                    @php
                                                        $ext = strtolower(pathinfo($row->file_name, PATHINFO_EXTENSION));
                                                    @endphp

                                                    @if ($ext === 'pdf')
                                                        <!-- PDF -->
                                                        <svg fill="none" aria-hidden="true" class="w-5 h-5 shrink-0" viewBox="0 0 20 21">
                                                            <g clip-path="url(#clip0_3173_1381)">
                                                                <path fill="#E2E5E7" d="M5.024.5c-.688 0-1.25.563-1.25 1.25v17.5c0 .688.562 1.25 1.25 1.25h12.5c.687 0 1.25-.563 1.25-1.25V5.5l-5-5h-8.75z"/>
                                                                <path fill="#B0B7BD" d="M15.024 5.5h3.75l-5-5v3.75c0 .688.562 1.25 1.25 1.25z"/>
                                                                <path fill="#CAD1D8" d="M18.774 9.25l-3.75-3.75h3.75v3.75z"/>
                                                                <path fill="#F15642" d="M16.274 16.75a.627.627 0 01-.625.625H1.899a.627.627 0 01-.625-.625V10.5c0-.344.281-.625.625-.625h13.75c.344 0 .625.281.625.625v6.25z"/>
                                                                <path fill="#fff" d="M3.998 12.342c0-.165.13-.345.34-.345h1.154c.65 0 1.235.435 1.235 1.269 0 .79-.585 1.23-1.235 1.23h-.834v.66c0 .22-.14.344-.32.344a.337.337 0 01-.34-.344v-2.814zm.66.284v1.245h.834c.335 0 .6-.295.6-.605 0-.35-.265-.64-.6-.64h-.834zM7.706 15.5c-.165 0-.345-.09-.345-.31v-2.838c0-.18.18-.31.345-.31H8.85c2.284 0 2.234 3.458.045 3.458h-1.19zm.315-2.848v2.239h.83c1.349 0 1.409-2.24 0-2.24h-.83zM11.894 13.486h1.274c.18 0 .36.18.36.355 0 .165-.18.3-.36.3h-1.274v1.049c0 .175-.124.31-.3.31-.22 0-.354-.135-.354-.31v-2.839c0-.18.135-.31.355-.31h1.754c.22 0 .35.13.35.31 0 .16-.13.34-.35.34h-1.455v.795z"/>
                                                                <path fill="#CAD1D8" d="M15.649 17.375H3.774V18h11.875a.627.627 0 00.625-.625v-.625a.627.627 0 01-.625.625z"/>
                                                            </g>
                                                            <defs>
                                                                <clipPath id="clip0_3173_1381">
                                                                    <path fill="#fff" d="M0 0h20v20H0z" transform="translate(0 .5)"/>
                                                                </clipPath>
                                                            </defs>
                                                        </svg>
                                                    @elseif (in_array($ext, ['doc', 'docx']))
                                                        <!-- Word -->
                                                        <svg class="w-6 h-6 text-gray-800 dark:text-blue-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M6 16v-3h.375a.626.626 0 0 1 .625.626v1.749a.626.626 0 0 1-.626.625H6Zm6-2.5a.5.5 0 1 1 1 0v2a.5.5 0 0 1-1 0v-2Z"/>
                                                            <path fill-rule="evenodd" d="M11 7V2h7a2 2 0 0 1 2 2v5h1a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1h-1a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2H3a1 1 0 0 1-1-1v-9a1 1 0 0 1 1-1h6a2 2 0 0 0 2-2Zm7.683 6.006 1.335-.024-.037-2-1.327.024a2.647 2.647 0 0 0-2.636 2.647v1.706a2.647 2.647 0 0 0 2.647 2.647H20v-2h-1.335a.647.647 0 0 1-.647-.647v-1.706a.647.647 0 0 1 .647-.647h.018ZM5 11a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h1.376A2.626 2.626 0 0 0 9 15.375v-1.75A2.626 2.626 0 0 0 6.375 11H5Zm7.5 0a2.5 2.5 0 0 0-2.5 2.5v2a2.5 2.5 0 0 0 5 0v-2a2.5 2.5 0 0 0-2.5-2.5Z" clip-rule="evenodd"/>
                                                            <path d="M9 7V2.221a2 2 0 0 0-.5.365L4.586 6.5a2 2 0 0 0-.365.5H9Z"/>
                                                        </svg>
                                                    @elseif (in_array($ext, ['xls', 'xlsx', 'csv']))
                                                        <!-- Excel -->
                                                        <svg class="w-6 h-6 text-gray-800 dark:text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                            <path fill-rule="evenodd" d="M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2 2 2 0 0 0 2 2h12a2 2 0 0 0 2-2 2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2V4a2 2 0 0 0-2-2h-7Zm1.018 8.828a2.34 2.34 0 0 0-2.373 2.13v.008a2.32 2.32 0 0 0 2.06 2.497l.535.059a.993.993 0 0 0 .136.006.272.272 0 0 1 .263.367l-.008.02a.377.377 0 0 1-.018.044.49.49 0 0 1-.078.02 1.689 1.689 0 0 1-.297.021h-1.13a1 1 0 1 0 0 2h1.13c.417 0 .892-.05 1.324-.279.47-.248.78-.648.953-1.134a2.272 2.272 0 0 0-2.115-3.06l-.478-.052a.32.32 0 0 1-.285-.341.34.34 0 0 1 .344-.306l.94.02a1 1 0 1 0 .043-2l-.943-.02h-.003Zm7.933 1.482a1 1 0 1 0-1.902-.62l-.57 1.747-.522-1.726a1 1 0 0 0-1.914.578l1.443 4.773a1 1 0 0 0 1.908.021l1.557-4.773Zm-13.762.88a.647.647 0 0 1 .458-.19h1.018a1 1 0 1 0 0-2H6.647A2.647 2.647 0 0 0 4 13.647v1.706A2.647 2.647 0 0 0 6.647 18h1.018a1 1 0 1 0 0-2H6.647A.647.647 0 0 1 6 15.353v-1.706c0-.172.068-.336.19-.457Z" clip-rule="evenodd"/>
                                                        </svg>
                                                    @else
                                                        <!-- Default -->
                                                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                            <path fill-rule="evenodd" d="M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-7Z" clip-rule="evenodd"/>
                                                        </svg>

                                                    @endif

                                                    {{ $row->file_name }}
                                                </span>
                                                <span class="flex text-xs font-normal text-gray-500 dark:text-gray-400 gap-2">
                                                    {{ $row->file_size }} kB 
                                                </span>
                                            </div>
                                            <div>
                                                <a href="{{ $row->fileUrl() }}" download="{{ $row->file_name }}" class="inline-flex self-center items-center p-2 text-sm font-medium text-center text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-600 dark:hover:bg-gray-500 dark:focus:ring-gray-600" type="button">
                                                    <svg class="w-4 h-4 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M14.707 7.793a1 1 0 0 0-1.414 0L11 10.086V1.5a1 1 0 0 0-2 0v8.586L6.707 7.793a1 1 0 1 0-1.414 1.414l4 4a1 1 0 0 0 1.416 0l4-4a1 1 0 0 0-.002-1.414Z"/>
                                                        <path d="M18 12h-2.55l-2.975 2.975a3.5 3.5 0 0 1-4.95 0L4.55 12H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Zm-3 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>

                                    @endif
                            
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="{{ $isTabLinksOpen ? '' : 'hidden' }} p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="links" role="tabpanel" aria-labelledby="links-tab">
                @if($links)
                    @foreach ($links as $row)
                        <div class="flex items-start gap-4 mb-4">
                            <div class="flex flex-col gap-2.5">
                                <div class="flex flex-col w-full max-w-[326px] leading-1.5 p-4  {{ $row->sender_id==auth()->id() ? 'rounded-s-xl rounded-br-xl border-green-200 bg-green-100 dark:bg-green-700' : 'rounded-e-xl rounded-es-xl border-gray-200 bg-gray-100 dark:bg-gray-700' }}">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                            @if (\Carbon\Carbon::parse($row->timestamp)->isToday())
                                                {{ \Carbon\Carbon::parse($row->timestamp)->format('H:i') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($row->timestamp)->format('d M Y H:i') }}
                                            @endif
                                        </span>
                                    </div>
                                    <p class="break-all text-sm font-normal py-2 text-gray-900 dark:text-white">
                                        {{-- {{ $row->content }} --}}
                                        {!! nl2br(preg_replace_callback(
                                            '/(https?:\/\/[^\s]+)/',
                                            fn($match) => '<a href="' . e($match[0]) . '" target="_blank" class="text-green-400 underline" rel="noopener noreferrer">' . e($match[0]) . '</a>',
                                            e($row->content)
                                        )) !!}
                                    </p>                            
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div id="drawer-contact" class="fixed top-0 right-0 z-50 w-screen md:w-96 h-screen p-4 overflow-y-auto transition-transform translate-x-full bg-white dark:bg-gray-800 border-s border-slate-600 {{ $isAddContactHeaderOpen ? 'transform-none' : '' }}" tabindex="-1" aria-labelledby="drawer-contact-label">
        <h5 id="drawer-contact-label" class="inline-flex items-center mb-6 text-base font-semibold text-gray-500 uppercase dark:text-gray-400">
            <svg class="w-6 h-6 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
            </svg>
            Edit contact
        </h5>

        <button type="button" @click="$dispatch('toggle-contact-header', null)" data-drawer-hide="drawer-contact" aria-controls="drawer-contact" class="mb-2 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white" >
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close menu</span>
        </button>

        <livewire:chat-contact-form :key="'chat-contact-form-2'" :isNewRecord="false" />

    </div>
</div>