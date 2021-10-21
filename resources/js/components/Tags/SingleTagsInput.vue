<template>
    <select
        v-if="loaded"
        class="w-full form-control form-select"
        :class="{ 'border-danger': errors.has(name) }"
        :id="name"
        :value="tags[0]"
        @input="$emit('input', [$event.target.value])"
    >
        <option value="" selected disabled>
            {{ __('Choose an option') }}
        </option>
        <option
            v-for="tag in availableTags"
            :key="tag"
            :value="tag"
        >
            {{ tag }}
        </option>
    </select>
</template>

<script>
export default {
    props: ['tags', 'type', 'subdomainId', 'name', 'suggestionLimit', 'errors'],

    model: {
        prop: 'tags',
    },

    data: () => ({
        loaded: false,
        availableTags: [],
    }),

    mounted() {
        this.getAvailableTags();
    },

    methods: {
        getAvailableTags() {
            let queryString = this.type ? `filter[type]=${this.type}` : '';

            if (this.subdomainId) {
                queryString += `&filter[subdomainId]=${this.subdomainId}`;
            }

            window.axios
                .get(`/nova-vendor/spatie/nova-tags-field?${queryString}`)
                .then(response => {
                    this.availableTags = response.data;

                    this.loaded = true;
                });
        },
    },
};
</script>
