import './bootstrap';
import { createApp } from 'vue';

//DRY is out of the window.
import App from './components/App.vue';
createApp(App).mount('#app');

//label mainpage list view.
import LabelList from './components//label/ShipmentList.vue';
createApp(LabelList).mount('#label-list');

//label individual view.
import LabelView from './components//label/ShipmentView.vue';
createApp(LabelView).mount('#label-view');