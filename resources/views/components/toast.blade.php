<div
    x-data="{
        toasts: [],
        addToast(event) {
            const detail = event.detail || {};
            const toast = {
                id: Date.now() + Math.random(),
                text: detail.slots?.text || detail.text || '',
                heading: detail.slots?.heading || detail.heading || null,
                variant: detail.dataset?.variant || detail.variant || 'default',
                position: detail.dataset?.position || detail.position || 'top-end',
                duration: detail.duration || 5000,
                show: true
            };
            
            this.toasts.push(toast);
            
            if (toast.duration && toast.duration > 0) {
                setTimeout(() => {
                    this.removeToast(toast.id);
                }, toast.duration);
            }
        },
        removeToast(id) {
            const index = this.toasts.findIndex(t => t.id === id);
            if (index > -1) {
                this.toasts[index].show = false;
                setTimeout(() => {
                    this.toasts.splice(index, 1);
                }, 300);
            }
        }
    }"
    @toast-show.window="addToast($event)"
    class="fixed z-50 pointer-events-none"
    :class="{
        'top-4 right-4': toasts.length > 0 && toasts[0].position === 'top-end',
        'top-4 left-4': toasts.length > 0 && toasts[0].position === 'top-start',
        'bottom-4 right-4': toasts.length > 0 && toasts[0].position === 'bottom-end',
        'bottom-4 left-4': toasts.length > 0 && toasts[0].position === 'bottom-start',
        'top-4 left-1/2 -translate-x-1/2': toasts.length > 0 && toasts[0].position === 'top',
        'bottom-4 left-1/2 -translate-x-1/2': toasts.length > 0 && toasts[0].position === 'bottom'
    }"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="toast.show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            class="pointer-events-auto mb-2 max-w-sm w-full bg-white dark:bg-zinc-800 rounded-lg shadow-lg border border-zinc-200 dark:border-zinc-700 p-4"
            :class="{
                'border-green-500 dark:border-green-600': toast.variant === 'success',
                'border-red-500 dark:border-red-600': toast.variant === 'danger',
                'border-yellow-500 dark:border-yellow-600': toast.variant === 'warning',
                'border-blue-500 dark:border-blue-600': toast.variant === 'info'
            }"
        >
            <div class="flex items-start gap-3">
                <div class="flex-1 min-w-0">
                    <template x-if="toast.heading">
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-1" x-text="toast.heading"></h3>
                    </template>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400" x-text="toast.text"></p>
                </div>
                <button
                    @click="removeToast(toast.id)"
                    class="flex-shrink-0 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </template>
</div>
