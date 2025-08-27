<div 
x-data="{ 
showDeleteModal: false, 
confirmDeleteId: null, 
showForwardModal: false, 
forwardMsgId: null
}">
    @if($showChat)
        <livewire:chat-header :senderId="$userModel->id" :authId="auth()->id()" :wire:key="'chat-'.$userModel->id" />

        <main class="md:ml-96 min-h-screen pt-12 bg-gray-900 pattern-grid">

            <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 mb-14 mt-8 p-5 pb-24">
                <div id="old_last">&nbsp;</div>

                @if($models->hasMorePages())
                    <div x-intersect.threshold.70="$wire.loadMore();$wire.dispatch('loadMore');"></div>
                    {{-- <button wire:click.prevent="loadMore" x-init='document.getElementById("old_last").scrollIntoView({ behavior: "smooth", block: "end", inline: "nearest" })''>Load more</button> --}}
                @endif

                @foreach($models->reverse()->values() as $row)
                {{-- <div id="message_{{ $row->id }}" wire:key='{{ $row->id }}' class="flex justify-end {{ $row->sender_id==auth()->id() ? '' : 'flex-row-reverse' }} flex-none gap-2.5 w-full" x-init='document.getElementById("message_last").scrollIntoView({ behavior: "smooth", block: "end", inline: "nearest" })'> --}}

                    <div id="message{{ $row->id }}" wire:key='{{ $row->id }}' class="flex justify-end {{ $row->sender_id==auth()->id() ? '' : 'flex-row-reverse' }} flex-none gap-1 w-full">
                        {{-- <img class="w-8 h-8 rounded-full" src="https://avatars.githubusercontent.com/u/167683279?v=4" alt="Jese image"> --}}
                        <div class="flex flex-col w-max md:w-auto max-w-lg leading-1.5 p-2  {{ $row->sender_id==auth()->id() ? 'rounded-s-xl rounded-br-xl border-green-200 bg-green-100 dark:bg-green-700' : 'rounded-e-xl rounded-es-xl border-gray-200 bg-gray-100 dark:bg-gray-700' }}">
                            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                {{-- <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ optional($row->sender)->name }}</span> --}}
                                @if($row->interaction_type == 'Forward')
                                    <span class="flex text-sm font-normal italic {{ $row->sender_id==auth()->id() ? 'text-gray-400' : 'text-gray-500' }}">
                                        <svg class="w-4 h-4 me-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M5.027 10.9a8.729 8.729 0 0 1 6.422-3.62v-1.2A2.061 2.061 0 0 1 12.61 4.2a1.986 1.986 0 0 1 2.104.23l5.491 4.308a2.11 2.11 0 0 1 .588 2.566 2.109 2.109 0 0 1-.588.734l-5.489 4.308a1.983 1.983 0 0 1-2.104.228 2.065 2.065 0 0 1-1.16-1.876v-.942c-5.33 1.284-6.212 5.251-6.25 5.441a1 1 0 0 1-.923.806h-.06a1.003 1.003 0 0 1-.955-.7A10.221 10.221 0 0 1 5.027 10.9Z"/>
                                        </svg>
                                        Forwarded
                                    </span>
                                @endif
                                {{-- <span class="text-xs font-normal text-gray-500 dark:text-gray-300">
                                    @if (\Carbon\Carbon::parse($row->timestamp)->isToday())
                                        {{ \Carbon\Carbon::parse($row->timestamp)->format('H:i') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($row->timestamp)->format('d M Y H:i') }}
                                    @endif
                                </span> --}}
                               
                            </div>
                            @if($row->parent && $row->interaction_type == 'Reply')
                                <div class="mt-1 flex justify-between ">
                                    <blockquote class="break-all w-full p-2 border-s-4 rounded border-gray-300 bg-gray-50 dark:border-gray-500 {{ $row->sender_id==auth()->id() ? 'dark:bg-gray-700' : 'dark:bg-gray-800' }}">
                                        <span class="text-xs text-gray-900 dark:text-green-400">{{ $row->parent->sender->name==auth()->user()->name ? "You" : $row->parent->sender?->contact->alias_name ?? $row->parent->sender?->email }}</span>
                                        
                                        @if($row->parent->message_type == 'Document')
                                            <a href="{{ $row->parent->fileUrl() }}" target="_blank" class="flex text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                                <svg class="w-5 h-5 mr-1 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $row->parent->file_name }} ({{ $row->parent->file_size }} kB)
                                            </a>
                                            
                                            <p class="text-sm text-gray-900 dark:text-white">{{ $row->parent->content }}</p>
                                        @else
                                            <p class="text-sm text-gray-900 dark:text-white">{{ $row->parent->content }}</p>
                                        @endif

                                    </blockquote>

                                    @if($row->parent->message_type == 'Image')
                                        <img src="{{ $row->parent->file_url }}" class="h-auto w-14 rounded-lg py-2" />
                                    @endif
                                </div>
                                
                            @endif
                            @if($row->file_url)
                                
                                @if($row->message_type == 'Image')
                                    {{-- <a href="{{ $row->fileUrl() }}" target="_blank" class="py-2">
                                        <img src="{{ $row->fileUrl() }}" class="h-auto rounded-lg" alt="{{ $row->file_name }}" />
                                    </a> --}}
                                    <div class="group relative my-1">
                                        <div class="absolute w-full h-full bg-gray-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg flex items-center justify-center">
                                            <a href="{{ url('/download?file=' . $row->fileUrl()) }}"  data-tooltip-target="download-image" class="inline-flex items-center justify-center rounded-full h-10 w-10 bg-white/30 hover:bg-white/50 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50">
                                                <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 18">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 1v11m0 0 4-4m-4 4L4 8m11 4v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3"/>
                                                </svg>
                                            </a>
                                            <div id="download-image" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                                                Download image
                                                <div class="tooltip-arrow" data-popper-arrow></div>
                                            </div>
                                        </div>
                                        <img src="{{ $row->fileUrl() }}" class="rounded-lg w-auto max-w-xs max-h-72 object-contain" />
                                    </div>
                                @elseif($row->message_type == 'Document')
                                    {{-- <a href="{{ $row->fileUrl() }}" target="_blank" class="flex text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                        <svg class="w-5 h-5 mr-1 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $row->file_name }} ({{ $row->file_size }} kB)
                                    </a> --}}
                                    <div class="flex justify-between items-start my-1 bg-gray-50 {{ $row->sender_id==auth()->id() ? 'dark:bg-green-800' : 'dark:bg-gray-600' }} rounded-xl p-2 w-full">
                                        <div class="me-2">
                                            <span class="break-all flex items-center gap-2 text-sm font-medium text-gray-900 dark:text-white pb-2">
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
                                            <a href="{{ url('/download?file=' . $row->fileUrl()) }}" class="inline-flex self-center items-center p-2 text-sm font-medium text-center text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-600 dark:hover:bg-gray-500 dark:focus:ring-gray-600" type="button">
                                                <svg class="w-4 h-4 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M14.707 7.793a1 1 0 0 0-1.414 0L11 10.086V1.5a1 1 0 0 0-2 0v8.586L6.707 7.793a1 1 0 1 0-1.414 1.414l4 4a1 1 0 0 0 1.416 0l4-4a1 1 0 0 0-.002-1.414Z"/>
                                                    <path d="M18 12h-2.55l-2.975 2.975a3.5 3.5 0 0 1-4.95 0L4.55 12H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Zm-3 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>

                                @endif
                                
                                   
                            @endif
                            <p class="break-all text-sm font-normal py-2 text-gray-900 dark:text-white">
                                {{-- {{ $row->content }} --}}
                                {!! nl2br(preg_replace_callback(
                                    '/(https?:\/\/[^\s]+)/',
                                    fn($match) => '<a href="' . e($match[0]) . '" target="_blank" class="text-green-400 underline" rel="noopener noreferrer">' . e($match[0]) . '</a>',
                                    e($row->content)
                                )) !!}
                            </p>
                            
                            <div class="flex justify-end space-x-2">
                                <span class="text-xs font-normal text-gray-500 dark:text-gray-300">
                                    @if (\Carbon\Carbon::parse($row->timestamp)->isToday())
                                        {{ \Carbon\Carbon::parse($row->timestamp)->format('H:i') }}
                                    @else
                                        {{-- {{ \Carbon\Carbon::parse($row->timestamp)->format('d M Y H:i') }} --}}
                                        {{ \Carbon\Carbon::parse($row->timestamp)->format('M j, Y H:i') }}
                                    @endif
                                </span>

                                <span class="text-xs font-normal text-gray-500 dark:text-gray-300">
                                    @if($row->read_status == 'Unread')
                                        <svg class="w-[18px] h-[18px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                    @else
                                        <svg class="w-[18px] h-[18px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd"/>
                                        </svg>

                                    @endif
                                </span>
                                
                            </div>
                      
                        </div>
                        <button title="Options" id="dropdownMenuIconButton{{ $row->id }}" data-dropdown-toggle="dropdownDots{{ $row->id }}" data-dropdown-placement="bottom-start" class="inline-flex self-start items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-800 dark:focus:ring-gray-600" type="button">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                            </svg>
                        </button>
                        <div id="dropdownDots{{ $row->id }}" class="z-50 hidden bg-white divide-y  rounded-lg shadow-lg w-40 dark:bg-gray-800  divide-gray-600">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownMenuIconButton{{ $row->id }}">
                                <li>
                                    <button type="button" wire:click='reply({{ $row->id }})' @click='document.getElementById("message").focus(); showForwardModal:false' class="flex items-center gap-2 w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                        <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.5 8.046H11V6.119c0-.921-.9-1.446-1.524-.894l-5.108 4.49a1.2 1.2 0 0 0 0 1.739l5.108 4.49c.624.556 1.524.027 1.524-.893v-1.928h2a3.023 3.023 0 0 1 3 3.046V19a5.593 5.593 0 0 0-1.5-10.954Z"/>
                                        </svg>

                                        <span>Reply</span>
                                    </button>
                                </li>
                                <li>
                                    <button 
                                    type="button" 
                                    data-modal-target="forward-modal" 
                                    data-modal-toggle="forward-modal"
                                    @click="$dispatch('get-contact-list', { targetMessageId: {{$row->id}} }); showForwardModal = true"
                                    class="flex items-center gap-2 w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                        <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.248 19C3.22 15.77 5.275 8.232 12.466 8.232V6.079a1.025 1.025 0 0 1 1.644-.862l5.479 4.307a1.108 1.108 0 0 1 0 1.723l-5.48 4.307a1.026 1.026 0 0 1-1.643-.861v-2.154C5.275 13.616 4.248 19 4.248 19Z"/>
                                        </svg>
                                        <span>Forward</span>
                                    </button>
                                </li>
                                
                            </ul>
                            <div class="py-2 {{ $row->sender_id==auth()->id() ? 'block' : 'hidden'}}">
                                <button 
                                    type="button" 
                                    data-modal-target="delete-modal" 
                                    data-modal-toggle="delete-modal"
                                    {{-- wire:click='remove({{ $row->id }})'  --}}
                                    @click="confirmDeleteId = {{ $row->id }}; showDeleteModal = true"
                                    class="{{ $row->sender_id==auth()->id() ? 'block' : 'hidden' }} flex items-center gap-2 w-full text-left px-1 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-white">
                                    <svg class="w-5 h-5 ms-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                    </svg>

                                    <span>Delete</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- <template x-if="messages[currentChannel] && messages[currentChannel].length > 0">
                    <template x-for="m in messages[currentChannel]" :key="m?.id">
                        <div :id="'message-' + m?.id" class="flex justify-end flex-none gap-2.5 w-full">
                            <div class="flex flex-col w-full max-w-[320px] leading-1.5 p-2 rounded-s-xl rounded-br-xl border-green-200 bg-green-100 dark:bg-green-700">
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Bonnie Green</span>
                                    <span x-text="m?.id" class="text-sm font-normal text-gray-500 dark:text-gray-400"></span>
                                </div>
                                <p x-text="m?.content" class="text-sm font-normal py-2.5 text-gray-900 dark:text-white"></p>
                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Sending...</span>
                            </div>

                            <button :id="'dropdownMenuIconButton-' + m?.id"
                                    :data-dropdown-toggle="'dropdownDots'+ m?.id"
                                    data-dropdown-placement="bottom-start"
                                    class="inline-flex self-center items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-800 dark:focus:ring-gray-600"
                                    type="button">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                    <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                </svg>
                            </button>

                            <div :id="'dropdownDots'+ m?.id"
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-40 dark:bg-gray-700 dark:divide-gray-600">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                    :aria-labelledby="'dropdownMenuIconButton-' + m?.id">
                                    <li>
                                        <button type="button"
                                                @click="messages[currentChannel] = []; localStorage.setItem('messages', JSON.stringify(messages))"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </template>
                </template> --}}

            </div>

            <div id="message_last">&nbsp;</div>
        </main>

        <footer class="bg-gray-800 shadow fixed bottom-0 md:left-96 left-0 right-0 md:z-20 z-10 p-2" style=" transform: none !important;">
                <form 
                wire:submit.prevent='send' 
                x-data 
                x-on:submit="window.dispatchEvent(new CustomEvent('close-picker'))"
                >  
             
                    @if($targetMessageId && count($selectedContacts) == 0)
                        <div class="flex justify-between">
                            <blockquote class="relative w-full text-left rounded p-2 mt-2 mb-3 border-s-4 border-gray-300 bg-gray-50 dark:border-gray-500 dark:bg-gray-700">
                                <div class="flex items-start justify-between">
                                    <!-- Bagian kiri: sender + text -->
                                    <div class="flex-1">
                                        <span class="block text-xs text-gray-900 dark:text-green-400">
                                            {{ $targetSender==auth()->user()->name ? "You" : $targetSender }}
                                        </span>

                                        <div class="flex items-start gap-2 mt-1">
                                            @if($targetMessageType == 'Document')
                                                <svg class="w-5 h-5 mr-1 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                                </svg>
                                                <p id="targetMessage" class="text-sm text-gray-900 dark:text-white">
                                                    {{ $targetMessageFileName }} {{ '(' . $targetMessageFileSize . ' kB) ' }}<br />
                                                    {{ $targetMessageText }}
                                                </p>
                                            @else
                                                <p id="targetMessage" class="text-sm text-gray-900 dark:text-white">
                                                    {{ $targetMessageText }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Bagian kanan: image + close -->
                                    <div class="flex items-start gap-2 ml-2">
                                        @if($targetMessageType == 'Image')
                                            <img src="{{ $targetMessageFileUrl }}" class="h-12 w-12 object-cover rounded-lg" />
                                        @endif

                                        <button wire:click='clearReply' type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Close</span>
                                        </button>
                                    </div>
                                </div>
                            </blockquote>

                        </div>
                    @endif

                    @if ($photo || $document) 
                    <div id="drawer-upload" class="fixed md:top-14 md:h-modal bottom-0 left-0 md:left-96 right-0 z-50 p-4 overflow-y-auto transition-transform bg-white dark:bg-gray-900 transform-none" tabindex="-1" aria-labelledby="drawer-bottom-label">
                        <h5 id="drawer-bottom-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 me-2.5" aria-modal="true" role="dialog" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                            </svg>
                            {{ $photo?->getClientOriginalName() ?? $document?->getClientOriginalName() }}
                        </h5>
                        <button type="button" wire:click='closeUploadDrawer' data-drawer-hide="drawer-upload" aria-controls="drawer-upload" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white" >
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close menu</span>
                        </button>
                        
                        @if($photo)
                            <div class="mb-2 p-4 h-full md:h-96 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden flex items-center justify-center">
                                <img class="h-72 w-auto max-w-full object-contain" 
                                    src="{{ $photo->temporaryUrl() }}"
                                    alt="Preview image">
                            </div>
                        @endif

                        @if($document)
                            <div class="mb-4 flex items-center justify-center">    
                                <div class="h-52 w-full max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 flex flex-col items-center justify-center text-center">
                                    <a href="#">
                                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">No preview available</h5>
                                    </a>
                                    <p class="font-normal text-gray-700 dark:text-gray-400">{{ $document->getSize() }} kB - {{  $document->getClientOriginalExtension() }}</p>
                                </div>
                            </div>

                        @endif

                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-square-text" viewBox="0 0 16 16">
                                <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
                                </svg>
                            </div>
                            <input wire:model='targetMessageId' type="hidden" class="block w-full p-3 ps-10 text-sm text-gray-500 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Type a message" autocomplete="false" />
                        
                            <input 
                                key="upload-{{ now()->timestamp }}"
                                wire:model.defer='message' 
                                {{-- wire:keydown="typing"
                                wire:keydown.debounce.1500ms="notTyping" --}}
                                type="text" 
                                id="message-upload" 
                                class="block w-full p-3 ps-10 text-sm text-gray-500 border border-gray-300 rounded-lg bg-gray-50 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:gray-blue-500 dark:focus:border-gray-500" placeholder="Type a message" autocomplete="off" />
                            <button type="submit" class="text-white absolute end-2.5 bottom-2 bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-1 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Send</button>
                        </div>
                    </div>

                    @else
                        <div id="drawer-upload"></div>
                    @endif


                    <label for="send" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Send</label>
                    
                    
                    <div class="relative flex items-center gap-2"
                        x-data="{
                            showPicker: false,
                            insertEmoji(e) {
                                $wire.addEmoji(e.detail.unicode);
                                {{-- this.showPicker = false; --}}
                            }
                        }"
                        @emoji-click="insertEmoji"
                        @click.outside="showPicker = false"
                        @keydown.escape.window="showPicker = false"
                        @close-picker.window="showPicker = false">

                        <button wire:loading.attr="disabled" id="dropdownTopButton" data-dropdown-toggle="dropdownTop" data-dropdown-offset-distance="10" data-dropdown-offset-skidding="74" data-dropdown-placement="top" class="text-gray-800 dark:text-white font-medium rounded-lg text-sm px-1 py-2.5 text-center inline-flex items-center" type="button">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div wire:loading.attr="disabled" id="dropdownTop" class="z-50 hidden bg-white rounded-lg shadow-sm w-48 dark:bg-gray-800">
                            <ul class="py-2 overflow-y-auto text-gray-700 dark:text-gray-200" aria-labelledby="dropdownUsersButton">
                                <li data-drawer-target="drawer-upload">
                                    <label class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                        <svg class="w-5 h-5 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                        </svg>
                                        Document
                                        <input type="file" wire:model="document" class="hidden" accept=".pdf,.doc,.docx,.txt,.xls,.xlsx,.csv,.ppt" />
                                    </label>
                                </li>
                                <li data-drawer-target="drawer-upload">
                                    <label class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                        Photo
                                        <input type="file" wire:model="photo" class="hidden" accept="image/*">
                                    </label>
                                </li>
                                {{-- <li>
                                    <button wire:click="openCamera" class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                        <svg class="me-2 w-6 h-6 text-red-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M7.5 4.586A2 2 0 0 1 8.914 4h6.172a2 2 0 0 1 1.414.586L17.914 6H19a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h1.086L7.5 4.586ZM10 12a2 2 0 1 1 4 0 2 2 0 0 1-4 0Zm2-4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z" clip-rule="evenodd"/>
                                        </svg>
                                        Camera
                                    </button>
                                </li> --}}
                            </ul>
                        </div>

                        <button wire:loading.attr="disabled" type="button" @click="showPicker = !showPicker" class="text-gray-800 dark:text-white font-medium rounded-lg text-sm px-1 py-2.5 text-center inline-flex items-center">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h.01M8.99 9H9m12 3a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM6.6 13a5.5 5.5 0 0 0 10.81 0H6.6Z"/>
                            </svg>
                        </button>
                        
                        <emoji-picker 
                            x-show="showPicker" 
                            x-transition 
                            class="absolute w-full bottom-16 z-50 mt-2 shadow-lg bg-white dark:bg-gray-800 border-gray-600 rounded-xl"
                        ></emoji-picker>

                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-square-text" viewBox="0 0 16 16">
                                <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
                                </svg>
                            </div>
                            <input wire:model='targetMessageId' type="hidden" class="block w-full p-3 ps-10 text-sm text-gray-500 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Type a message" autocomplete="false" />
                        
                            @if (!$photo || !$document) 
                                <textarea wire:ignore focus
                                    type="text" 
                                    id="message" 
                                    key="{{ now()->timestamp }}"
                                    class="block w-full p-3 ps-10 text-sm text-gray-500 border border-gray-300 rounded-lg bg-gray-50 focus:ring-gray-500 focus:border-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-gray-500 dark:focus:border-gray-500 resize-none overflow-hidden" 
                                    style="min-height: 40px; max-height: 120px;"
                                    rows="1"
                                    placeholder="Type a message"
                                    autocomplete="off"
                                    wire:model.defer='message' 
                                    wire:keydown="typing"
                                    wire:keyup.debounce.1500ms="notTyping"
                                    rows="1"
                                    x-data="{ resize() { $el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 120) + 'px'; } }"
                                    x-init="
                                        resize(); 
                                        $watch('$wire.message', () => resize());
                                    "
                                    x-on:input="resize()"
                                    x-on:keydown.enter="if (!event.shiftKey) { 
                                        event.preventDefault(); 
                                        $wire.send(); 
                                        window.dispatchEvent(new CustomEvent('close-picker')); 
                                    }"
                                    >
                                </textarea>
                           
                                <button type="submit" wire:offline.attr="disabled" @disabled(empty($message)) class="text-white absolute end-2.5 bottom-2 bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-1 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                    Send
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
              
        </footer>

        @if($models->count() > 0)
        <div x-show="showDeleteModal"
            @keydown.escape.window="showDeleteModal = false"
            x-transition 
            id="delete-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="delete-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this message?</h3>
                        <button @click="$wire.remove(confirmDeleteId); showModal = false" data-modal-hide="delete-modal" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Yes, I'm sure
                        </button>
                        <button @click="showModal = false" data-modal-hide="delete-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, cancel</button>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Forward modal -->
        <div x-show="showForwardModal"
            x-cloak
            @keydown.escape.window="showForwardModal = false; $wire.set('selectedContacts', []); $wire.set('targetMessageId', null);"
            x-transition 
            data-modal-backdrop="static"
            x-on:save-forwarded-message="showForwardModal = false;"
            id="forward-modal" tabindex="-1" aria-hidden="true" class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md  max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Forward message to
                        </h3>
                        <button @click="showForwardModal = false; $wire.set('selectedContacts', []); $wire.set('targetMessageId', null);" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="forward-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    
                    <form wire:submit.prevent='sendForwardMessage'>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5 flex flex-col h-[400px]"> 
                            <!-- Input fixed di atas -->
                            <div class="mb-2">
                                <input wire:model.live='searchContact' 
                                    type="text" 
                                    name="forward_to" 
                                    id="forward_to" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" 
                                    placeholder="Search name or email" 
                                    autocomplete="off" />
                            </div>

                            <!-- Scrollable list -->
                            <div class="flex-1 overflow-y-auto">
                                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($contactList as $c)
                                        <li class="py-1">
                                            <label class="flex items-center p-3 text-base font-bold text-gray-900 rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white cursor-pointer">
                                                
                                                <!-- Checkbox -->
                                                <input type="checkbox" 
                                                    value="{{ $c['acquaintance_id'].'|'.$c['name'] }}" 
                                                    wire:model.live="selectedContacts" 
                                                    class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600" />
                                                
                                                <!-- Avatar -->
                                                <div class="flex-shrink-0 ms-3">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $c['avatar_color'] }}">
                                                        <span class="text-xs font-medium text-white">
                                                            {{ $c['avatar_initials'] }} 
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Name + About -->
                                                <div class="flex-1 min-w-0 ms-4">
                                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                        {{$c['name']}}
                                                    </p>
                                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                        {{$c['about']}}
                                                    </p>
                                                </div>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        @if(count($selectedContacts)>0)
                            <!-- Modal footer -->
                            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                <div class="flex flex-wrap gap-2 flex-1 text-sm">
                                    @foreach($selectedContacts as $item)
                                        <span class="px-2 py-1 bg-gray-600 text-white rounded text-sm">
                                            {{ explode('|', $item)[1] ?? '' }}
                                        </span>
                                    @endforeach
                                </div>
                                <button wire:loading.attr="disabled" type="submit" @click="showForwardModal = false; document.querySelectorAll('[modal-backdrop]').forEach(el => el.remove());document.body.classList.remove('overflow-hidden');" data-modal-hide="forward-modal" class="ml-4 text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                    Send
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        @endif
    
    @else

    <header class="bg-white dark:bg-gray-800 shadow fixed left-0 md:left-96 right-0 md:z-40 z-0 md:hidden">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-4">
            <div class="flex items-center">
                <button 
                    data-drawer-target="drawer-navigation"
                    data-drawer-toggle="drawer-navigation"
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

            </div>
        </div>
    </header>
    
    <main class="md:ml-96 min-h-screen pt-14 bg-gray-900 pattern-grid">

        <section class="bg-white dark:bg-gray-900 pattern-grid">
            <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">Use ChatsApp for free</h1>
                <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 lg:px-48 dark:text-gray-400">Here at ChatsApp we focus on markets where technology, innovation, and capital can unlock long-term value and drive economic growth.</p>
                <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
                    <a href="#"
                    data-drawer-target="drawer-navigation"
                    data-drawer-toggle="drawer-navigation"
                    aria-controls="drawer-navigation"
                    class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                        Get started
                        <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        {{-- <a wire:navigate href="{{route('push')}}" class="btn btn-outline-primary btn-block">Make a Push Notification!</a> --}}

       
    </main>

    @endif

     <div wire:offline>
        <div id="toast-danger" class="z-50 fixed bottom-5 right-5 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800" role="alert">
            <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                </svg>
                <span class="sr-only">Error icon</span>
            </div>
            <div class="ms-3 text-sm font-normal">You are now offline. <br> Please check your internet connection.</div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-danger" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        </div>
     </div>
</div>

@script
<script>
   
    $wire.on('loadMore', (event) => {
        console.log('LOADMORE');
        setTimeout(function() {
            initDropdowns();
        }, 500);
    });

    $wire.on('open-chat', (event) => {
        // console.log(`room.${event.userId1}.${event.userId2}`);
        // window.Echo.private(`room.${event.userId1}.${event.userId2}`)
        console.log('EVENT',event);
      
        const roomName = `room.${event.userId1}.${event.userId2}`;
        const oldRoom = localStorage.getItem("old_channel");

        if (event.action === 'init' && oldRoom) {
            Echo.leave(oldRoom);
            console.log('LEAVE ROOM', oldRoom);
            localStorage.removeItem("old_channel");
        }
   
       
        Echo.join(roomName)
        // .listen('MessageSent', (e) => {
        //     console.log(e.message);
        // })
        .here((users) => {
            const isOnline = users.some(u => u.id === event.userId2);
            console.log('ONLINE', isOnline, users);
            users.forEach(user => {
                $dispatch('presence-online', { 
                    userId: user.id, 
                    isOnline: true,
                    forceBroadcast: true 
                });
            });
            
            console.log('YOU ARE IN ROOM', `room.${event.userId1}.${event.userId2}`, users);
            localStorage.setItem("old_channel", roomName);
        })
        .joining((user) => {
            console.log('USER JOINING', user, event.userId1, event.userId2);
            if (user.id === event.userId2) {
                $dispatch('presence-online', { 
                    userId: user.id, 
                    isOnline: true,
                    forceBroadcast: true 
                });
            }
            
            if (user.id === event.userId1) {
                $dispatch('presence-online', { 
                    userId: user.id, 
                    isOnline: true,
                    forceBroadcast: true 
                });
            }

            setTimeout(() => {
                Echo.private(roomName).whisper('request-status', {
                    requester: user.id
                });
            }, 500);
        })
        .leaving((user) => {
            console.log('USER LEAVING', user, event.userId1, event.userId2);
            if (user.id === event.userId1) {
                $dispatch('presence-online', { userId: user.id, isOnline: false });
            }

            if (user.id === event.userId2) {
                $dispatch('presence-online', { userId: user.id, isOnline: false });
            }
        })
        .error((error) => {
            console.error(error);
        });

        window.Echo.private(roomName)
        .listenForWhisper('request-status', (payload) => {
            console.log('WHISPER', payload);
            if (payload.requester !== event.userId1) {
                $dispatch('presence-online', {
                    userId: event.userId1,
                    isOnline: true,
                    respondTo: payload.requester,
                    forceBroadcast: true 
                });
            }

            if (payload.requester !== event.userId2) {
                $dispatch('presence-online', {
                    userId: event.userId2,
                    isOnline: true,
                    respondTo: payload.requester,
                    forceBroadcast: true 
                });
            }
        });

        window.Echo.private(`room.${event.userId1}.${event.userId2}`).listen("PushMessage", (e) => {
            console.log('ROOM', `room.${event.userId1}.${event.userId2}`);
            console.log("EVENT", event);
            console.log("BROADCAST", e);
            // Livewire.dispatch('$refresh');
            // $wire.dispatch('$refresh');
            $wire.dispatch('triggerMessage', e);

            // $dispatch('presence-online', { userId: event.userId1, isOnline: true });
            // $dispatch('presence-online', { userId: event.userId2, isOnline: true });

            setTimeout(function() {
                document.getElementById("message_last").scrollIntoView({ behavior: "smooth", block: "end", inline: "nearest" });          
                initDropdowns();
                initFlowbite();
            }, 1000);
         
        });

        // window.Echo.private(`chat.3`)
        // .listen('UserTyping', (e) => {
        //     console.log('UserTyping event received:', e);
        //     Livewire.dispatch('user-typing', { userId: 1, isTyping: e.isTyping });
        // });

        // window.Echo.join(`room.${event.userId1}.${event.userId2}`)
        //     .subscribed(()=>{
        //     window.livewire.dispatch('subscribed')
        //     })
        //     .here(users => {
        //     window.livewire.dispatch('here', users)
        //     })
        //     .joining(user => {
        //     window.livewire.dispatch('joining', user)
        //     console.log("joining" + JSON.stringify(user))
        //     })
        //     .leaving(user => {
        //     window.livewire.dispatch('leaving', user)
        //     console.log("joining" + JSON.stringify(user))
        //     })
        //     .listen('NewMessage', message => {
        //     window.livewire.dispatch('newMessage', message)
        //     console.log("newMessage" + JSON.stringify(message))
        //     })
        //     .listen('MessageDelete', data => {
        //     window.livewire.dispatch('removeMessage', data)
        //     console.log("deleteMessage" + JSON.stringify(data))
        //     })
        //     .error(error => {
        //     // sweetAlert(error)
        //     })

        setTimeout(function() {
            setTimeout(() => {
                document.getElementById('message').focus()
            }, 200)
            document.getElementById("message_last").scrollIntoView({ behavior: "smooth", block: "end", inline: "nearest" });          
            initDropdowns();
            initFlowbite();
        }, 1000);
    });

    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
        // Runs immediately before a commit's payload is sent to the server...
    
        respond(() => {
            // Runs after a response is received but before it's processed...
        })
    
        succeed(({ snapshot, effect }) => {
            console.log('COMMIT', component, commit, snapshot, effect);
            initDropdowns();
            initFlowbite();
        })
    
        fail(() => {
            // Runs if some part of the request failed...
        })
    })

   
</script>
@endscript