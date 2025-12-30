import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();


import { sendEmail } from './email-form';
import { sidebarNavigation } from './sidebar-navigation';
import { setupUserModal } from './user-modal';

sendEmail();
setupUserModal();