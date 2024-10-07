<!-- resources/views/marketing/banner.blade.php -->
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="flex items-center space-x-4">
                        {{-- Logo Circle --}}
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </div>
                        </div>

                        {{-- Title and Tagline --}}
                        <div>
                            <h1 class="text-2xl font-bold text-blue-900">WebLet</h1>
                            <p class="text-blue-700 font-medium">Backend-Driven Frontend, Simplified</p>
                        </div>
                    </div>

                    {{-- Feature Grid --}}
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="font-semibold text-blue-900">Sincronización Automática</h3>
                            <p class="text-sm text-gray-600">Backend y frontend siempre en sintonía</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="font-semibold text-blue-900">Estado en Servidor</h3>
                            <p class="text-sm text-gray-600">Gestión robusta y centralizada</p>
                        </div>
                    </div>

                    {{-- Call to Action --}}
                    <div class="mt-8 flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                        <x-button class="justify-center">
                            Comenzar
                        </x-button>
                        <x-button class="justify-center bg-blue-100 text-blue-700 hover:bg-blue-200">
                            Ver Documentación
                        </x-button>
                    </div>

                    {{-- Footer --}}
                    <div class="mt-6 text-center text-sm text-gray-500">
                        www.weblet.dev
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
