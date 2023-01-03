import {createApp} from 'vue';

import App from './components/App.vue';
import Tree from './components/Tree';

const partialApp = createApp(App)
partialApp.component("Tree", Tree);
partialApp.mount('#partialEditor');