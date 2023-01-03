<template>
    <div class="row g-0">
        <div class="col-12 col-xl-3 col-lg-3">
            <div class="card rounded-0">
                <div class="card-header px-3 py-2 fw-bold">
                    <i class="fas fa-cubes me-2"></i>
                    Partials
                </div>
                <div class="card-body p-0 position-relative" style="min-height: 500px; max-height: 500px; overflow-y: auto">
                    <Tree :items="partials" @click="onPartialClick"/>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-9 col-lg-9" v-if="selectedPartial">
            <div class="card card-partial-content">
                <div class="card-header px-3 py-2 d-flex justify-content-between align-items-center">
                    <div class="fw-bold mb-0" >
                        <i class="fas fa-cube text-success"></i>
                        {{selectedPartial.name}}
                    </div>
                    <div class="d-flex">
                        <div class="partial-action text-dark">
                            <i class="fas fa-info-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 position-relative">
                    <div class="partial-loading" v-if="partialLoading">
                        <div class="spinner-border text-white spinner-border-sm"
                            role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="partital-actions">
                        <div class="partial-action" @click="savePartial">
                            <i class="fas fa-save text-success"></i>
                            <span>Save</span>
                        </div>                        
                        <div class="partial-action text-dark" @click="deletePartial">
                            <i class="fas fa-trash"></i>
                        </div>                        
                    </div>
                    <div class="partial-content">
                        <Codemirror
                            :value="selectedPartialContent"
                            :options="cmOptions"
                            border
                            :height="400"
                            @change="onPartialContentChanged"
                        />                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Tree from './Tree';
import {reactive, ref, onMounted} from 'vue';
import axios from '../axios';
import Codemirror from "codemirror-editor-vue3";

import "codemirror/mode/php/php.js";
import 'codemirror/lib/codemirror.css';
import "codemirror/theme/idea.css";

export default {
    components: {Tree, Codemirror},
    setup () {
        const apiURL          = window.OCMS?.apiURL;
        const partials        = ref([]);
        const selectedPartial = ref(null);
        const selectedPartialContent = ref("");
        const partialLoading  = ref(false);
        const cmOptions =  ref({
            mode: "application/x-httpd-php", // Language mode
            theme: "idea",
            lineNumbers: true, // Show line number
            smartIndent: true,
            indentUnit: 4,
            indentWithTabs: true,
            tabSize: 4,
            lineWrapping: true,       
            matchBrackets: true,     
        });

        //Methods
        const fetchPartials = async () => {
            try {
                partialLoading.value = true;
                const { data } = await axios.get(apiURL + '/tree');

                if(data.success) {
                    partials.value = data.data
                }
            } catch (err) {
                console.log(err)
            } finally {
                partialLoading.value = false;
            }
        };

        const getPartialContent = async (partial) => {
            try {
                partialLoading.value = true;
                const path     = partial.path + `/${partial.basename}`;
                const { data } = await axios.post(apiURL + '/content', {path});

                if(data.success) {
                    selectedPartialContent.value = data.data.content
                }
            } catch (err) {
                console.log(err)
            } finally {
                partialLoading.value = false;
            }
        };

        const onPartialClick = async ({event, partial, index, level}) => {
            if(partial.is_dir) {
                partial.is_open = !partial.is_open;
            } else {
                selectedPartialContent.value = "";
                selectedPartial.value = partial;
                partial.is_open = true;

                await getPartialContent(partial);
            }

            partials.value[level][index] = partial;
        }

        const 
        onPartialContentChanged = async (e) => {
            selectedPartialContent.value = e;
        },
        savePartial = async () => {
            try {
                partialLoading.value = true;
                const partial  = selectedPartial.value;
                const path     = partial.path + `/${partial.basename}`;
                const { data } = await axios.post(apiURL + '/content', {path, content: selectedPartialContent.value});

                if(data.success) {
                    Notyf.success("Saved changes...");
                } else {
                    Notyf.error("Something went wrong!");
                }
            } catch (err) {
                console.log(err)
            } finally {
                partialLoading.value = false;
            }
        },
        deletePartial = async () => {
            try {
                partialLoading.value = true;
                const partial  = selectedPartial.value;
                const path     = partial.path + `/${partial.basename}`;
                const { data } = await axios.post(apiURL + '/delete', {path});

                if(data.success) {
                    Notyf.success("Deleted partial successfully!");
                    selectedPartial.value = ""
                    selectedPartialContent.value = ""
                    await fetchPartials()
                } else {
                    Notyf.error("Something went wrong!");
                }
            } catch (err) {
                console.log(err)
            } finally {
                partialLoading.value = false;
            }
        };

        onMounted(async () => {
            await fetchPartials()
        });

        return {
            partials,
            selectedPartial,
            selectedPartialContent,
            fetchPartials,
            onPartialClick,
            cmOptions,
            onPartialContentChanged,
            savePartial,
            deletePartial
        }
    }
}
</script>