<div>
    @if($showChat)
        <livewire:chat-header :senderId="$userModel->id" :authId="auth()->id()" :wire:key="'chat-'.$userModel->id" />

        <main class="md:ml-96 h-auto pt-12 bg-gray-900">

            <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 mb-14 mt-8 p-5 pb-24">
                <div id="old_last">&nbsp;</div>

                @if($models->hasMorePages())
                    <div x-intersect.threshold.70="$wire.loadMore();$wire.dispatch('loadMore');"></div>
                    {{-- <button wire:click.prevent="loadMore" x-init='document.getElementById("old_last").scrollIntoView({ behavior: "smooth", block: "end", inline: "nearest" })''>Load more</button> --}}
                @endif

              

                @foreach($models->reverse()->values() as $row)
                {{-- <div id="message_{{ $row->id }}" wire:key='{{ $row->id }}' class="flex justify-end {{ $row->sender_id==auth()->id() ? '' : 'flex-row-reverse' }} flex-none gap-2.5 w-full" x-init='document.getElementById("message_last").scrollIntoView({ behavior: "smooth", block: "end", inline: "nearest" })'> --}}

                    <div id="message{{ $row->id }}" wire:key='{{ $row->id }}' class="flex justify-end {{ $row->sender_id==auth()->id() ? '' : 'flex-row-reverse' }} flex-none gap-2.5 w-full">
                        {{-- <img class="w-8 h-8 rounded-full" src="https://avatars.githubusercontent.com/u/167683279?v=4" alt="Jese image"> --}}
                        <div class="flex flex-col w-full max-w-[320px] leading-1.5 p-4  {{ $row->sender_id==auth()->id() ? 'rounded-s-xl rounded-br-xl border-green-200 bg-green-100 dark:bg-green-700' : 'rounded-e-xl rounded-es-xl border-gray-200 bg-gray-100 dark:bg-gray-700' }}">
                            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ optional($row->sender)->name }}</span>
                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">{{ date('d M Y H:i',strtotime($row->timestamp)) }}</span>
                            </div>
                            @if($row->parent)
                                <div class="flex justify-between bg-gray-50 dark:bg-gray-800">
                                    <blockquote class="p-2  border-s-4 border-gray-300 bg-gray-50 dark:border-gray-500 dark:bg-gray-800">
                                        <span class="text-xs text-gray-900 dark:text-green-400">{{ $row->parent->sender->name==auth()->user()->name ? "You" : $row->parent->sender->name }}</span>
                                        
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
                                    <a href="{{ $row->fileUrl() }}" target="_blank" class="py-2">
                                        <img src="{{ $row->fileUrl() }}" class="h-auto rounded-lg" alt="{{ $row->file_name }}" />
                                    </a>
                                @elseif($row->message_type == 'Document')
                                    <a href="{{ $row->fileUrl() }}" target="_blank" class="flex text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                        <svg class="w-5 h-5 mr-1 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $row->file_name }} ({{ $row->file_size }} kB)
                                    </a>
                                @endif
                                
                                   
                            @endif
                            <p class="text-sm font-normal py-2 text-gray-900 dark:text-white">{{ $row->content }}</p>
                            {{-- <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Delivered</span> --}}
                        </div>
                        <button title="Test" id="dropdownMenuIconButton{{ $row->id }}" data-dropdown-toggle="dropdownDots{{ $row->id }}" data-dropdown-placement="bottom-start" class="inline-flex p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-800 dark:focus:ring-gray-600" type="button">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                            </svg>
                        </button>
                        <div id="dropdownDots{{ $row->id }}" class="z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-40 dark:bg-gray-600 dark:divide-gray-600">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownMenuIconButton{{ $row->id }}">
                                <li>
                                    <button type="button" wire:click='reply({{ $row->id }})' @click='document.getElementById("message").focus();' class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Reply</button>
                                </li>
                                <li>
                                    <button type="button" wire:click='remove({{ $row->id }})' class="{{ $row->sender_id==auth()->id() ? 'block' : 'hidden' }} px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Delete</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="message_last">&nbsp;</div>


            <footer class="bg-white dark:bg-gray-800 shadow fixed bottom-0 md:left-96 left-0 right-0 z-0 p-2">
                <form wire:submit.prevent='send'>  

                    @if($targetMessageId)
                        <div class="flex justify-between">
                            <blockquote class="p-2 my-2 border-s-4 border-gray-300 bg-gray-50 dark:border-gray-500 dark:bg-gray-800">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-900 dark:text-green-400">{{ $targetSender==auth()->user()->name ? "You" : $targetSender }}</span>
                                    <button wire:click='clearReply' type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                            
                                <div class="flex justify-between">
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
                            </blockquote>

                            @if($targetMessageType == 'Image')
                                <img src="{{ $targetMessageFileUrl }}" class="h-auto w-14 rounded-lg py-2" />
                            @endif
                        </div>
                    @endif

                    @if ($photo || $document) 
                    <div id="drawer-bottom-example" class="fixed bottom-0 left-0 right-0 z-40 w-full p-4 overflow-y-auto transition-transform bg-white dark:bg-gray-800 transform-none" tabindex="-1" aria-labelledby="drawer-bottom-label">
                        <h5 id="drawer-bottom-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400"><svg class="w-4 h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>{{ $photo?->getClientOriginalName() ?? $document?->getClientOriginalName() }}</h5>
                        <button type="button" wire:click='closeModal' data-drawer-hide="drawer-bottom-example" aria-controls="drawer-bottom-example" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white" >
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close menu</span>
                        </button>
                        
                        @if($photo)
                            <div class="mb-4 aspect-video bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden flex items-center justify-center">
                                <img class="w-full h-full object-contain" 
                                    src="{{ $photo->temporaryUrl() }}"
                                    alt="Preview gambar">
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
                        
                            <input required 
                                key="{{ now()->timestamp }}"
                                wire:model.defer='message' 
                                wire:keydown="typing"
                                wire:keyup.debounce.1500ms="notTyping"
                                type="text" 
                                id="message" 
                                class="block w-full p-3 ps-10 text-sm text-gray-500 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Type a message" autocomplete="off" />
                            <button type="submit" @disabled(empty($message)) class="text-white absolute end-2.5 bottom-2 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-1 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Send</button>
                        </div>
                    </div>
                      
                    @endif


                    <label for="send" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Send</label>
                    <div class="relative flex items-center gap-2">

                        <button id="dropdownTopButton" data-dropdown-toggle="dropdownTop" data-dropdown-placement="top" class="text-gray-800 dark:text-white font-medium rounded-lg text-sm px-1 py-2.5 text-center inline-flex items-center" type="button">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                            </svg>
                        </button>

                        <!-- Dropdown menu -->
                        <div id="dropdownTop" class="z-10 hidden bg-white rounded-lg shadow-sm w-48 dark:bg-gray-800">
                            <ul class="py-2 overflow-y-auto text-gray-700 dark:text-gray-200" aria-labelledby="dropdownUsersButton">
                                <li>
                                    <label class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                        <svg class="w-5 h-5 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                        </svg>
                                        Document
                                        <input type="file" wire:model="document" class="hidden" accept=".pdf,.doc,.docx,.txt">
                                    </label>
                                </li>
                                <li>
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

                        <button class="text-gray-800 dark:text-white font-medium rounded-lg text-sm px-1 py-2.5 text-center inline-flex items-center">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h.01M8.99 9H9m12 3a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM6.6 13a5.5 5.5 0 0 0 10.81 0H6.6Z"/>
                            </svg>
                        </button>
                        
                       
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-square-text" viewBox="0 0 16 16">
                                <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
                                </svg>
                            </div>
                            <input wire:model='targetMessageId' type="hidden" class="block w-full p-3 ps-10 text-sm text-gray-500 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Type a message" autocomplete="false" />
                        
                            <input required 
                                key="{{ now()->timestamp }}"
                                wire:model.defer='message' 
                                wire:keydown="typing"
                                wire:keyup.debounce.1500ms="notTyping"
                                type="text" 
                                id="message" 
                                class="block w-full p-3 ps-10 text-sm text-gray-500 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Type a message" autocomplete="off" />
                            <button type="submit" @disabled(empty($message)) class="text-white absolute end-2.5 bottom-2 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-1 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Send</button>
                        </div>
                    </div>
                </form>
              
            </footer>
        </main>
    
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
    
    <main class="md:ml-96 h-auto pt-14 bg-gray-900">

        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">We invest in the world’s potential</h1>
                <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 lg:px-48 dark:text-gray-400">Here at Flowbite we focus on markets where technology, innovation, and capital can unlock long-term value and drive economic growth.</p>
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

    </main>

    @endif
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
            Livewire.dispatch('$refresh');
            $wire.dispatch('$refresh');
            $wire.dispatch('triggerMessage', e);

            $dispatch('presence-online', { userId: event.userId1, isOnline: true });
            $dispatch('presence-online', { userId: event.userId2, isOnline: true });

            setTimeout(function() {
                document.getElementById("message_last").scrollIntoView({ behavior: "smooth", block: "end", inline: "nearest" });          
                initDropdowns();
                initFlowbite();
            }, 500);
         
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
            document.getElementById("message_last").scrollIntoView({ behavior: "smooth", block: "end", inline: "nearest" });          
            initDropdowns();
            initFlowbite();
        }, 1000);
    });

   
</script>
@endscript