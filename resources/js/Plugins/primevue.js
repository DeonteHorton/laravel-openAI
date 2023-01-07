import PrimeVue from 'primevue/config'

import Tooltip from 'primevue/tooltip';
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import Card from 'primevue/card';
import ScrollTop from 'primevue/scrolltop';
import Skeleton from 'primevue/skeleton';

import ToastService from 'primevue/toastservice';
import Toast from 'primevue/toast';


import 'primeicons/primeicons.css';
import "primevue/resources/primevue.min.css";
import 'primevue/resources/themes/tailwind-light/theme.css';

// adds services and components to an app that's already mounted
export default {
    install: (app) => {
        app.use(ToastService)
        app.directive('tooltip', Tooltip)
        app.component('p-button', Button)
        app.component('p-dropdown', Dropdown)
        app.component('p-input-text', InputText)
        app.component('p-toast', Toast)
        app.component('p-card', Card)
        app.component('p-scrolltop', ScrollTop)
        app.component('p-skeleton', Skeleton)
        app.use(PrimeVue, {
            ripple: true
        })
    }
}