import '../css/app.css';
import { createInertiaApp } from '@inertiajs/vue3';
import { addIcons, OhVueIcon } from 'oh-vue-icons';
import {
    HiArchive,
    HiChevronDown,
    HiChevronLeft,
    HiChevronRight,
    HiClipboardCheck,
    HiFolder,
    HiPencilAlt,
    HiPlus,
    HiUserAdd,
    HiUserGroup,
} from 'oh-vue-icons/icons/hi';
import type { App } from 'vue';

addIcons(
    HiArchive,
    HiChevronDown,
    HiChevronLeft,
    HiChevronRight,
    HiClipboardCheck,
    HiFolder,
    HiPencilAlt,
    HiPlus,
    HiUserAdd,
    HiUserGroup,
);

createInertiaApp({
    withApp(app: App) {
        app.component('VIcon', OhVueIcon);
    },
});
