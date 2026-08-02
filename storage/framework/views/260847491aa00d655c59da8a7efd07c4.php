<?php $__env->startSection('title', 'Chat dengan '.($otherUser->store_name ?? $otherUser->name)); ?>
<?php $__env->startSection('content'); ?>
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">
    <div class="rounded-lg border border-ink-100 bg-white shadow-soft overflow-hidden flex flex-col h-[75vh] sm:h-[70vh]">

        <div class="flex items-center gap-3 px-4 sm:px-5 py-3.5 border-b border-ink-100 shrink-0">
            <a href="<?php echo e(route('chat.index')); ?>" class="p-1.5 -ml-1.5 rounded-full hover:bg-ink-100 active:scale-90 transition-all">
                <svg class="h-5 w-5 text-ink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            </a>
            <span class="relative shrink-0">
                <span class="h-10 w-10 rounded-full bg-gradient-to-br from-forest-400 to-forest-600 text-white text-sm font-bold flex items-center justify-center">
                    <?php echo e(strtoupper(substr($otherUser->store_name ?? $otherUser->name, 0, 1))); ?>

                </span>
                <span id="chat-online-dot" class="absolute bottom-0 right-0 h-3 w-3 rounded-full ring-2 ring-white <?php echo e($otherUser->isOnline() ? 'bg-forest-500' : 'bg-ink-300'); ?>"></span>
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-ink-800 truncate"><?php echo e($otherUser->store_name ?? $otherUser->name); ?></p>
                <p id="chat-online-label" class="text-xs <?php echo e($otherUser->isOnline() ? 'text-forest-600' : 'text-ink-400'); ?>"><?php echo e($otherUser->isOnline() ? 'Online' : 'Offline'); ?></p>
            </div>
            <?php if($conversation->product): ?>
            <a href="<?php echo e(route('products.show', $conversation->product)); ?>" class="hidden sm:flex items-center gap-2 rounded-md border border-ink-100 px-2.5 py-1.5 hover:border-clay-300 transition-colors shrink-0">
                <img src="<?php echo e($conversation->product->image_url); ?>" class="h-8 w-8 rounded-lg object-cover" alt="">
                <span class="text-xs font-medium text-ink-600 max-w-[120px] truncate"><?php echo e($conversation->product->name); ?></span>
            </a>
            <?php endif; ?>
        </div>

        <div id="chat-messages" class="flex-1 overflow-y-auto px-4 sm:px-5 py-4 space-y-3 bg-ink-50/40">
            <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex <?php echo e($message->sender_id === auth()->id() ? 'justify-end' : 'justify-start'); ?>" data-message-id="<?php echo e($message->id); ?>">
                <div class="max-w-[75%] rounded-lg px-3.5 py-2 text-sm shadow-soft <?php echo e($message->sender_id === auth()->id() ? 'gradient-brand text-white rounded-br-sm' : 'bg-white text-ink-700 border border-ink-100 rounded-bl-sm'); ?>">
                    <p class="whitespace-pre-line break-words"><?php echo e($message->body); ?></p>
                    <p class="text-[10px] mt-1 <?php echo e($message->sender_id === auth()->id() ? 'text-white/70' : 'text-ink-400'); ?>"><?php echo e($message->created_at->format('H:i')); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-center text-sm text-ink-400 py-10">Mulai percakapan dengan mengirim pesan pertama 👋</p>
            <?php endif; ?>
        </div>

        <?php if($quickReplies->isNotEmpty()): ?>
        <div class="flex items-center gap-2 overflow-x-auto px-3 sm:px-4 py-2.5 border-t border-ink-100 shrink-0 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            <span class="shrink-0 text-[11px] font-semibold text-ink-400 uppercase tracking-wide">Balasan Cepat</span>
            <?php $__currentLoopData = $quickReplies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button type="button" onclick="useQuickReply(<?php echo e(Js::from($qr->body)); ?>)"
                class="shrink-0 rounded-full border border-ink-200 bg-white px-3 py-1.5 text-xs font-medium text-ink-600 hover:border-clay-300 hover:text-clay-600 transition-colors">
                <?php echo e($qr->title); ?>

            </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('seller.quick-replies.index')); ?>" class="shrink-0 text-xs font-semibold text-clay-600 hover:underline">Kelola</a>
        </div>
        <?php endif; ?>

        <form id="chat-form" action="<?php echo e(route('chat.store', $conversation)); ?>" method="POST" class="flex items-center gap-2 px-3 sm:px-4 py-3 border-t border-ink-100 shrink-0">
            <?php echo csrf_field(); ?>
            <input type="text" name="body" id="chat-input" placeholder="Tulis pesan..." autocomplete="off" required maxlength="2000"
                class="flex-1 rounded-full border border-ink-200 bg-white px-4 py-2.5 text-sm shadow-soft focus:outline-none focus:ring-2 focus:ring-clay-400 focus:border-clay-400 transition">
            <button type="submit" class="btn-tactile shrink-0 h-10 w-10 rounded-full gradient-brand text-white flex items-center justify-center shadow-glow hover:brightness-110 transition">
                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
            </button>
        </form>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    const box = document.getElementById('chat-messages');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const dot = document.getElementById('chat-online-dot');
    const label = document.getElementById('chat-online-label');
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const meId = <?php echo e(auth()->id()); ?>;
    const pollUrl = "<?php echo e(route('chat.poll', $conversation)); ?>";

    window.useQuickReply = function (text) {
        input.value = text;
        input.focus();
        input.setSelectionRange(text.length, text.length);
    };

    let lastId = 0;
    box.querySelectorAll('[data-message-id]').forEach(el => {
        lastId = Math.max(lastId, parseInt(el.dataset.messageId, 10));
    });

    function scrollToBottom() {
        box.scrollTop = box.scrollHeight;
    }
    scrollToBottom();

    function appendMessage(msg) {
        const mine = msg.sender_id === meId;
        const wrap = document.createElement('div');
        wrap.className = 'flex ' + (mine ? 'justify-end' : 'justify-start');
        wrap.dataset.messageId = msg.id;
        wrap.innerHTML = `
            <div class="max-w-[75%] rounded-lg px-3.5 py-2 text-sm shadow-soft ${mine ? 'gradient-brand text-white rounded-br-sm' : 'bg-white text-ink-700 border border-ink-100 rounded-bl-sm'}">
                <p class="whitespace-pre-line break-words"></p>
                <p class="text-[10px] mt-1 ${mine ? 'text-white/70' : 'text-ink-400'}"></p>
            </div>`;
        wrap.querySelector('p').textContent = msg.body;
        wrap.querySelectorAll('p')[1].textContent = msg.created_at;
        box.appendChild(wrap);
        lastId = Math.max(lastId, msg.id);
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const body = input.value.trim();
        if (!body) return;
        input.value = '';
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ body }),
        })
        .then(r => r.ok ? r.json() : Promise.reject())
        .then(data => {
            appendMessage(data.message);
            scrollToBottom();
        })
        .catch(() => { input.value = body; });
    });

    function poll() {
        fetch(pollUrl + '?after=' + lastId, { headers: { 'Accept': 'application/json' } })
            .then(r => r.ok ? r.json() : Promise.reject())
            .then(data => {
                const wasAtBottom = box.scrollHeight - box.scrollTop - box.clientHeight < 60;
                (data.messages || []).forEach(appendMessage);
                if (wasAtBottom) scrollToBottom();

                dot.classList.toggle('bg-forest-500', data.other_online);
                dot.classList.toggle('bg-ink-300', !data.other_online);
                label.textContent = data.other_online ? 'Online' : 'Offline';
                label.classList.toggle('text-forest-600', data.other_online);
                label.classList.toggle('text-ink-400', !data.other_online);
            })
            .catch(() => {});
    }

    setInterval(poll, 4000);
})();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ecommerce\resources\views/chat/show.blade.php ENDPATH**/ ?>