<?php
// Floating AI chatbot widget (storefront)
// Public endpoint: POST /api/chat  { messages: [{role, content}] }
// Placed bottom-right, above the WhatsApp button.

// AI assistant logo (chat bubble + sparkle) — reusable, adapts to currentColor.
$aiLogo = '<svg viewBox="0 0 24 24" aria-hidden="true" style="width:1.3em;height:1.3em;display:block">'
    . '<path d="M12 3C6.9 3 3 6.6 3 11c0 2.4 1.2 4.6 3.1 6-.2 1.2-.8 2.3-1.6 3.1 1.6-.1 3.2-.6 4.5-1.4 1.1.3 2.3.5 3.5.5 5.1 0 9-3.6 9-8s-3.9-8-9-8z" fill="currentColor" fill-opacity=".18" stroke="currentColor" stroke-width="1.6"/>'
    . '<path d="M12 8c.4 2.6 1.4 3.6 4 4-2.6.4-3.6 1.4-4 4-.4-2.6-1.4-3.6-4-4 2.6-.4 3.6-1.4 4-4z" fill="currentColor"/>'
    . '</svg>';
?>
<style>
.cb-root *{box-sizing:border-box;font-family:"Noto Sans Lao",sans-serif}
.cb-root,.cb-root button,.cb-root textarea{font-family:"Noto Sans Lao",sans-serif}
.cb-panel{
  width:min(92vw,380px);height:min(72vh,620px);
  display:flex;flex-direction:column;overflow:hidden;
  background:hsl(var(--card));
  border:1px solid hsl(var(--border));
  border-radius:20px;
  box-shadow:0 24px 60px -12px rgba(0,0,0,.35),0 0 0 1px rgba(0,0,0,.02);
  margin-bottom:14px;
  transform-origin:bottom right;
}
.cb-header{
  position:relative;padding:14px 16px;
  background:linear-gradient(135deg,hsl(var(--primary)),hsl(221.2 83.2% 44%));
  color:hsl(var(--primary-foreground));
}
.cb-header::after{
  content:"";position:absolute;left:0;right:0;bottom:0;height:32px;
  background:linear-gradient(to top,rgba(0,0,0,.12),transparent);
}
.cb-avatar{
  width:40px;height:40px;border-radius:50%;flex-shrink:0;
  background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;
  font-size:18px;position:relative;
}
.cb-online{position:absolute;right:-1px;bottom:-1px;width:11px;height:11px;border-radius:50%;
  background:#34d399;border:2px solid hsl(var(--primary));}
.cb-iconbtn{
  width:32px;height:32px;border-radius:10px;display:flex;align-items:center;justify-content:center;
  background:rgba(255,255,255,.12);color:hsl(var(--primary-foreground));
  border:none;cursor:pointer;transition:background .15s;
}
.cb-iconbtn:hover{background:rgba(255,255,255,.28)}
.cb-messages{
  flex:1;overflow-y:auto;padding:16px 14px;display:flex;flex-direction:column;gap:14px;
  background:hsl(var(--background));
}
.cb-messages::-webkit-scrollbar{width:7px}
.cb-messages::-webkit-scrollbar-thumb{background:hsl(var(--muted-foreground)/.35);border-radius:99px}
.cb-messages::-webkit-scrollbar-track{background:transparent}
.cb-row{display:flex;gap:8px;align-items:flex-end;max-width:100%}
.cb-row.user{flex-direction:row-reverse}
.cb-bubble-avatar{
  width:28px;height:28px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;
  font-size:13px;
}
.cb-bubble-avatar.bot{background:hsl(var(--primary)/.12);color:hsl(var(--primary))}
.cb-bubble-avatar.me{background:hsl(var(--muted));color:hsl(var(--muted-foreground))}
.cb-bubble{
  max-width:78%;padding:10px 13px;border-radius:16px;font-size:14px;line-height:1.55;white-space:pre-wrap;
  word-wrap:break-word;overflow-wrap:anywhere;
}
.cb-row.bot .cb-bubble{
  background:hsl(var(--card));border:1px solid hsl(var(--border));
  color:hsl(var(--foreground));border-bottom-left-radius:5px;
}
.cb-row.user .cb-bubble{
  background:linear-gradient(135deg,hsl(var(--primary)),hsl(221.2 83.2% 47%));
  color:hsl(var(--primary-foreground));border-bottom-right-radius:5px;
}
.cb-time{font-size:10px;color:hsl(var(--muted-foreground));margin-top:3px;opacity:.8}
.cb-row.user .cb-time{text-align:right}
.cb-md h2,.cb-md h3,.cb-md h4{font-weight:700;margin:6px 0 4px;line-height:1.3}
.cb-md h2{font-size:16px}.cb-md h3{font-size:15px}.cb-md h4{font-size:14px}
.cb-md ul,.cb-md ol{margin:4px 0 4px 18px;padding:0}
.cb-md li{margin:2px 0}
.cb-md code{background:hsl(var(--muted));padding:1px 5px;border-radius:5px;font-size:12.5px;
  font-family:ui-monospace,SFMono-Regular,Menlo,monospace}
.cb-md strong{font-weight:700}
.cb-md .cb-space{height:6px}
.cb-typing{display:flex;gap:4px;align-items:center;padding:12px 14px}
.cb-dot{width:7px;height:7px;border-radius:50%;background:hsl(var(--muted-foreground)/.6);animation:cb-bounce 1.2s infinite}
.cb-dot:nth-child(2){animation-delay:.15s}.cb-dot:nth-child(3){animation-delay:.3s}
@keyframes cb-bounce{0%,60%,100%{transform:translateY(0);opacity:.5}30%{transform:translateY(-5px);opacity:1}}
.cb-suggest{display:flex;flex-wrap:wrap;gap:8px;padding:0 14px 12px;background:hsl(var(--background))}
.cb-chip{
  font-size:12.5px;padding:7px 12px;border-radius:99px;cursor:pointer;
  background:hsl(var(--card));border:1px solid hsl(var(--border));color:hsl(var(--foreground));
  transition:.15s;
}
.cb-chip:hover{border-color:hsl(var(--primary));color:hsl(var(--primary));background:hsl(var(--primary)/.06)}
.cb-inputbar{
  display:flex;align-items:flex-end;gap:8px;padding:10px 12px;
  border-top:1px solid hsl(var(--border));background:hsl(var(--card));
}
.cb-textarea{
  flex:1;resize:none;border:1px solid hsl(var(--border));border-radius:14px;
  padding:10px 12px;font-size:14px;line-height:1.4;max-height:120px;min-height:42px;
  background:hsl(var(--background));color:hsl(var(--foreground));outline:none;transition:.15s;
}
.cb-textarea:focus{border-color:hsl(var(--primary));box-shadow:0 0 0 3px hsl(var(--primary)/.15)}
.cb-send{
  width:42px;height:42px;border-radius:14px;flex-shrink:0;border:none;cursor:pointer;
  background:linear-gradient(135deg,hsl(var(--primary)),hsl(221.2 83.2% 47%));
  color:hsl(var(--primary-foreground));display:flex;align-items:center;justify-content:center;
  transition:.15s;
}
.cb-send:hover:not(:disabled){filter:brightness(1.08)}
.cb-send:disabled{opacity:.45;cursor:not-allowed}
.cb-fab{
  width:56px;height:56px;border-radius:50%;border:none;cursor:pointer;
  background:linear-gradient(135deg,hsl(var(--primary)),hsl(221.2 83.2% 44%));
  color:hsl(var(--primary-foreground));
  display:flex;align-items:center;justify-content:center;font-size:24px;
  box-shadow:0 12px 28px -6px hsl(var(--primary)/.6);
  transition:transform .18s;
}
.cb-fab:hover{transform:scale(1.06)}
.cb-fab::before{
  content:"";position:absolute;inset:0;border-radius:50%;
  box-shadow:0 0 0 0 hsl(var(--primary)/.5);animation:cb-pulse 2.4s infinite;
}
.cb-fab-wrap{position:relative;display:flex}
@keyframes cb-pulse{
  0%{box-shadow:0 0 0 0 hsl(var(--primary)/.5)}
  70%{box-shadow:0 0 0 16px hsl(var(--primary)/0)}
  100%{box-shadow:0 0 0 0 hsl(var(--primary)/0)}
}
.cb-badge{
  position:absolute;top:-4px;right:-4px;background:#ef4444;color:#fff;font-size:9px;font-weight:700;
  padding:2px 6px;border-radius:99px;letter-spacing:.3px;
}
</style>

<div x-data="chatbot()" data-notranslate notranslate style="position:fixed; bottom:96px; right:24px; z-index:10000;" class="cb-root flex flex-col items-end">

    <!-- Chat panel -->
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         class="cb-panel">

        <!-- Header -->
        <div class="cb-header flex items-center justify-between gap-2">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="cb-avatar">
                    <?= $aiLogo ?>
                    <span class="cb-online"></span>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold leading-tight truncate">ຜູ້ຊ່ວຍ AI</p>
                    <p class="text-[11px] leading-tight truncate" style="color:hsl(var(--primary-foreground)/.85)">ອອນລາຍ · ສອບຖາມໄດ້ທັນທີ</p>
                </div>
            </div>
            <div class="flex items-center gap-1.5">
                <button @click="reset()" class="cb-iconbtn" title="ເລີ່ມສົນທະນາໃໝ່">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px"><path d="M23 4v6h-6M1 20v-6h6"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                </button>
                <button @click="open = false" class="cb-iconbtn" title="ປິດ">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" style="width:18px;height:18px"><path d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
            </div>
        </div>

        <!-- Messages -->
        <div x-ref="scroll" class="cb-messages">
            <template x-for="(msg, i) in messages" :key="i">
                <div class="cb-row" :class="msg.role === 'user' ? 'user' : 'bot'">
                    <div class="cb-bubble-avatar" :class="msg.role === 'user' ? 'me' : 'bot'" x-show="msg.role !== 'user'">
                        <?= $aiLogo ?>
                        <svg x-show="msg.role === 'user'" viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px"><path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm0 2c-4 0-8 2-8 5v1h16v-1c0-3-4-5-8-5z"/></svg>
                    </div>
                    <div>
                        <div class="cb-bubble"
                             :class="msg.role === 'user' ? '' : 'cb-md'"
                             x-html="msg.role === 'user' ? msg.content.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>') : cbRenderMarkdown(msg.content)"></div>
                        <div class="cb-time" x-text="msg.time"></div>
                    </div>
                </div>
            </template>

            <div x-show="loading" class="cb-row bot">
                <div class="cb-bubble-avatar bot"><?= $aiLogo ?></div>
                <div class="cb-bubble cb-typing">
                    <span class="cb-dot"></span><span class="cb-dot"></span><span class="cb-dot"></span>
                </div>
            </div>
        </div>

        <!-- Suggestion chips -->
        <div class="cb-suggest" x-show="showSuggestions && !loading">
            <template x-for="(s, i) in suggestions" :key="i">
                <button class="cb-chip" @click="suggest(s)" x-text="s"></button>
            </template>
        </div>

        <!-- Input -->
        <form @submit.prevent="send()" class="cb-inputbar">
            <textarea x-ref="ta" x-model="input" @keydown.enter.prevent="send()" @input="autoGrow()" rows="1"
                      placeholder="ພິມຂໍ້ຄວາມຂອງທ່ານ..." class="cb-textarea"></textarea>
            <button type="submit" :disabled="loading || !input.trim()" class="cb-send" title="ສົ່ງ">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            </button>
        </form>
    </div>

    <!-- Toggle button -->
    <div class="cb-fab-wrap" x-show="!open">
        <button @click="toggle()" class="cb-fab" title="ຜູ້ຊ່ວຍ AI">
            <?= $aiLogo ?>
        </button>
        <span class="cb-badge">AI</span>
    </div>
</div>

<script>
function chatbot() {
    return {
        open: false,
        input: '',
        loading: false,
        showSuggestions: true,
        messages: [],
        suggestions: [
            'ແນະນຳສິນຄ້າຍອດນິຍົມ',
            'ວິທີສັ່ງຊື້ແລະຊໍາລະເງິນ',
            'ເວລາແລະຄ່າຈັດສົ່ງ',
            'ຕິດຕໍ່ພະນັກງານໄດ້ແນວໃດ?'
        ],
        now() {
            return new Date().toLocaleTimeString('lo-LA', { hour: '2-digit', minute: '2-digit' });
        },
        init() {
            this.messages = [{ role: 'assistant', content: <?= json_encode('ສະບາຍດີ! ຂ້ອຍເປັນຜູ້ຊ່ວຍ AI ຂອງຮ້ານ ທ່ານສາມາດສອບຖາມໄດ້ທັນທີເລື່ອງ: ສິນຄ້າ, ລາຄາ, ການສັ່ງຊື້ ແລະການຈັດສົ່ງ 💬') ?>, time: this.now() }];
        },
        toggle() {
            this.open = !this.open;
            if (this.open) { this.$nextTick(() => this.autoGrow()); this.scrollToBottom(); }
        },
        reset() {
            this.messages = [{ role: 'assistant', content: <?= json_encode('ສະບາຍດີ! ຂ້ອຍເປັນຜູ້ຊ່ວຍ AI ຂອງຮ້ານ ທ່ານສາມາດສອບຖາມໄດ້ທັນທີເລື່ອງ: ສິນຄ້າ, ລາຄາ, ການສັ່ງຊື້ ແລະການຈັດສົ່ງ 💬') ?>, time: this.now() }];
            this.showSuggestions = true;
            this.input = '';
            this.scrollToBottom();
        },
        autoGrow() {
            const ta = this.$refs.ta;
            if (!ta) return;
            ta.style.height = 'auto';
            ta.style.height = Math.min(ta.scrollHeight, 120) + 'px';
        },
        scrollToBottom() {
            this.$nextTick(() => {
                const el = this.$refs.scroll;
                if (el) el.scrollTop = el.scrollHeight;
            });
        },
        suggest(text) {
            this.input = text;
            this.send();
        },
        async send() {
            const text = this.input.trim();
            if (!text || this.loading) return;
            this.messages.push({ role: 'user', content: text, time: this.now() });
            this.input = '';
            this.showSuggestions = false;
            this.loading = true;
            this.autoGrow();
            this.scrollToBottom();

            try {
                const res = await fetch('<?= url('/api/chat') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ messages: this.messages.map(m => ({ role: m.role, content: m.content })) })
                });
                const data = await res.json();
                const reply = (data.choices && data.choices[0] && data.choices[0].message && data.choices[0].message.content)
                    || data.reply
                    || 'ຂໍອະໄພ, ເກີດຂໍ້ຜິດພາດ. / Sorry, something went wrong.';
                this.messages.push({ role: 'assistant', content: reply, time: this.now() });
            } catch (e) {
                this.messages.push({ role: 'assistant', content: 'ຂໍອະໄພ, ເຊື່ອມຕໍ່ບໍ່ໄດ້. / Sorry, connection failed.', time: this.now() });
            } finally {
                this.loading = false;
                this.scrollToBottom();
            }
        },
        cbRenderMarkdown(raw) {
            if (!raw) return '';
            let s = raw.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            s = s.replace(/`([^`]+)`/g, '<code>$1</code>');
            s = s.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
            s = s.replace(/(^|[^*])\*([^*]+)\*/g, '$1<em>$2</em>');
            const lines = s.split('\n');
            let out = '', inUl = false, inOl = false;
            const closeLists = () => { if (inUl) { out += '</ul>'; inUl = false; } if (inOl) { out += '</ol>'; inOl = false; } };
            for (let line of lines) {
                let m;
                if ((m = line.match(/^\s*[-*]\s+(.*)$/))) {
                    if (!inUl) { closeLists(); out += '<ul class="cb-ul">'; inUl = true; }
                    out += '<li>' + m[1] + '</li>';
                } else if ((m = line.match(/^\s*\d+\.\s+(.*)$/))) {
                    if (!inOl) { closeLists(); out += '<ol class="cb-ol">'; inOl = true; }
                    out += '<li>' + m[1] + '</li>';
                } else if ((m = line.match(/^###\s+(.*)$/))) {
                    closeLists(); out += '<h4>' + m[1] + '</h4>';
                } else if ((m = line.match(/^##\s+(.*)$/))) {
                    closeLists(); out += '<h3>' + m[1] + '</h3>';
                } else if ((m = line.match(/^#\s+(.*)$/))) {
                    closeLists(); out += '<h2>' + m[1] + '</h2>';
                } else if (line.trim() === '') {
                    closeLists(); out += '<div class="cb-space"></div>';
                } else {
                    closeLists(); out += line + '<br>';
                }
            }
            closeLists();
            return out;
        }
    }
}
</script>
