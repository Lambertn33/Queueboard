import '../css/app.css';
import { createInertiaApp } from '@inertiajs/vue3';
import { addIcons, OhVueIcon } from 'oh-vue-icons';
import { HiChevronDown, HiClipboardCheck, HiFolder, HiUserGroup } from 'oh-vue-icons/icons/hi';
import type { App } from 'vue';

addIcons(HiChevronDown, HiClipboardCheck, HiFolder, HiUserGroup);

createInertiaApp({
    withApp(app: App) {
        app.component('VIcon', OhVueIcon);
    },
});
