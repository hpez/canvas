<template>
    <multiselect v-model="value"
                 :placeholder="trans.topics.forms.select"
                 :tag-placeholder="trans.topics.forms.tag"
                 :options="options"
                 :multiple="false"
                 :taggable="true"
                 @input="onChange"
                 @tag="addTopic"
                 label="name"
                 track-by="slug">
    </multiselect>
</template>

<script>
    import _ from 'lodash';
    import Multiselect from "vue-multiselect";
    import {store} from "../screens/posts/store";

    export default {
        props: {
            topics: {
                type: Array,
                required: false
            },
            assigned: {
                type: Array,
                required: false
            }
        },

        components: {
            Multiselect
        },

        data() {
            const allTopics = this.topics.map(obj => {
                let filtered = {};

                filtered["name"] = obj.name;
                filtered["slug"] = obj.slug;

                return filtered;
            });

            return {
                options: allTopics,
                value: this.assigned ? this.assigned : [],
                trans: JSON.parse(Canvas.lang)
            };
        },

        methods: {
            onChange(value, id) {
                store.syncTopic(value);
                this.update();
            },

            addTopic(searchQuery) {
                const topic = {
                    name: searchQuery,
                    slug: this.slugify(searchQuery)
                };

                this.options.push(topic);

                this.value = {
                    name: topic.name,
                    slug: topic.slug
                };

                store.syncTopic(topic);
                this.update();
            },

            update: _.debounce(function (e) {
                this.$parent.update();
            }, 900)
        }
    };
</script>
