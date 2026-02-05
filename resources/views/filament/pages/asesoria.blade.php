<x-filament-panels::page>
    <div style="position: fixed; inset: 0; background-color: #0c1214; display: flex; align-items: center; justify-content: center; z-index: 40; margin-top: 4rem; height: calc(100vh - 4rem);">
        
        <div class="w-full max-w-md overflow-hidden bg-white rounded-xl shadow-2xl dark:bg-gray-800" style="animation: fadeInUp 0.5s ease-out;">
            <!-- Header Green -->
            <!-- Header Green -->
            <div style="background-color: #00a884; height: 160px; width: 100%; position: relative;">
                <div style="position: absolute; bottom: 40px; left: 50%; transform: translateX(-50%);">
                     <div style="background-color: #25D366; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 16px rgba(0,0,0,0.2); border: 4px solid white;">
                        <svg style="width: 40px; height: 40px; color: white;" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                     </div>
                </div>
            </div>

            <!-- Content -->
            <div class="px-8 pt-4 pb-8 text-center" style="margin-top: -20px;">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Contacto Directo</h2>
                <p class="text-gray-500 mb-6 text-sm">Ingresa tu Nombre Completo para registrar la consulta y abrir el chat.</p>
                
                <form wire:submit.prevent="submit" class="space-y-4">
                    <input 
                        wire:model="client_name" 
                        type="text" 
                        placeholder="Escribe tu Nombre Completo..." 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        style="border-color: #e5e7eb;"
                    >
                    @error('client_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    
                    <button 
                        type="submit"
                        style="background-color: #25D366; color: white; width: 100%; padding: 12px; border-radius: 8px; font-weight: bold; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); transition: transform 0.1s;"
                        onmouseover="this.style.backgroundColor='#1da851'"
                        onmouseout="this.style.backgroundColor='#25D366'"
                        onmousedown="this.style.transform='scale(0.98)'"
                        onmouseup="this.style.transform='scale(1)'"
                    >
                        ABRIR WHATSAPP
                    </button>
                    
                    <p class="text-xs text-gray-400 mt-4">Serás redirigido a WhatsApp Web/App</p>
                </form>
            </div>
        </div>
    </div>

    <!-- Event Listener for Redirect -->
    @script
    <script>
        $wire.on('open-whatsapp', (event) => {
            let phone = '529991730976';
            // The name is passed in the event detail, structure depends on Livewire version
            // Filament usually wraps detail. Accessing event.name or event[0].name
            let name = event.name || (event[0] && event[0].name) || ''; 
            let text = `Hola, soy ${name}. Me gustaría solicitar asesoría.`;
            
            let url = `https://web.whatsapp.com/send?phone=${phone}&text=${encodeURIComponent(text)}`;
            window.open(url, '_blank');
        });
    </script>
    @endscript

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-filament-panels::page>
