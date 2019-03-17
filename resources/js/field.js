Nova.booting((Vue, router, store) => {
    Vue.component('index-child-select', require('./components/IndexField'))
    Vue.component('detail-child-select', require('./components/DetailField'))
    Vue.component('form-child-select', require('./components/FormField'))
})
