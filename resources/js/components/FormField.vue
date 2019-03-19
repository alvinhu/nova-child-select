<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <select
                :id="field.attribute"
                v-model="value"
                class="w-full form-control form-select"
                :class="errorClasses"
                :disabled="disabled"
            >
                <option value selected>{{ __('Choose an option') }}</option>
                <option
                    :key="option.value"
                    :value="option.value"
                    v-for="option in options"
                >{{ option.label }}</option>
            </select>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ["resourceName", "resourceId", "field"],

    data() {
        return {
            parentValues: {},
            loaded: false,
            options: []
        };
    },

    mounted() {
        this.watchedComponents.forEach(component => {
            console.log("WATCHING "+ component.fieldAttribute)

            let attribute = "value";

            if (component.field.component === "belongs-to-field") {
                attribute = "selectedResource";
            }

            component.$watch(
                attribute,
                value => {
                    this.parentValues[component.fieldAttribute] =
                        value && attribute == "selectedResource"
                            ? value.value
                            : value;

                    console.log("update options with ")
                    console.log(component.fieldAttribute)
                    console.log(value)

                    console.log("parent values")
                    console.log(this.parentValues)

                    this.updateOptions();
                },
                { immediate: true }
            );
        });
    },

    computed: {
        watchedComponents() {
            return this.$parent.$children.filter(component => {
                return this.isWatchingComponent(component);
            });
        },

        disabled() {
            return this.options.length == 0;
        }
    },

    methods: {
        setInitialValue() {
            this.value = "";
        },

        fill(formData) {
            formData.append(this.field.attribute, this.value || "");
        },

        updateOptions() {
            this.value = "";
            this.loaded = false;
            this.options = [];

            if (this.parentValue != {}) {
                Nova.request()
                    .post(
                        "/nova-vendor/child-select/options?resource=" +
                            this.resourceName,
                            {
                                resourceName: this.resourceName,
                                attribute: this.field.attribute,
                                parents: this.parentValues
                            }
                    )
                    .then(response => {
                        this.loaded = true;
                        this.options = response.data;
                        let optionValueExists = false;
                        this.options.forEach(option => {
                            if (option.value == this.field.value) {
                                optionValueExists = true;
                                this.value = option.value;
                            }
                        });
                    });
            }
        },

        isWatchingComponent(component) {
            return (
                component.field !== undefined &&
                this.field.parentAttributes.includes(component.field.attribute)
            );
        }
    }
};
</script>
