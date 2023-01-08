import PrimeVue from 'primevue/config'

import Tooltip from 'primevue/tooltip';
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import Card from 'primevue/card';
import ScrollTop from 'primevue/scrolltop';
import Skeleton from 'primevue/skeleton';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from 'primevue/dialog';
import ConfirmPopup from 'primevue/confirmpopup'
import InputNumber from 'primevue/inputnumber'
import DropDown from 'primevue/dropdown'

import ToastService from 'primevue/toastservice';
import Toast from 'primevue/toast';
import ConfirmationService from 'primevue/confirmationservice'

import 'primeicons/primeicons.css';
import "primevue/resources/primevue.min.css";
import 'primevue/resources/themes/tailwind-light/theme.css';

// adds services and components to an app that's already mounted
export default {
    install: (app) => {
        app.use(ToastService)
        app.use(ConfirmationService)
        app.directive('tooltip', Tooltip)
        app.component('p-button', Button)
        app.component('p-dropdown', Dropdown)
        app.component('p-input-text', InputText)
        app.component('p-toast', Toast)
        app.component('p-card', Card)
        app.component('p-scrolltop', ScrollTop)
        app.component('p-skeleton', Skeleton)
        app.component('p-datatable', DataTable)
        app.component('p-column', Column)
        app.component('p-dialog', Dialog)
        app.component('p-confirm-popup', ConfirmPopup)
        app.component('p-input-number', InputNumber)
        app.component('p-dropdown', DropDown)
        app.use(PrimeVue, {
            ripple: true
        })
    }
}