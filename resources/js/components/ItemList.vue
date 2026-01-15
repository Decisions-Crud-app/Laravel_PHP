<template>
   <div>
     <b-form-select v-model="filter" @change="load">
         <option value="">All</option>
         <option value="active">Active</option>
         <option value="inactive">Inactive</option>
</b-form-select>

<b-table :items="items" @row-clicked="edit"></b-table>

<b-modal v-model="show">
   <b-form-input v-model="form.name"/>
   <b-form-textarea v-model="form.description"/>
   <b-form-select v-model="form.status" :options="['active','inactive']"/>
   <b-button @click="save">Save</b-button>
</b-modal>
</div>
</template>

<script>
import axios from 'axios'
export default{
    data() {
        return {items: [], filter:'', form: {}, show: false}
    },
    mounted() { this.load() },
    methods: {
        load() {
            axios.get('/api/items', {params: { Status: this.filter } })
            .then(r => this.items = r.data)
        },
        edit(item) {
            this.form = { ...item}
            this.show = true
        },
        save() {
            axios.put(`/api/items/${this.form.uuid}`, this.form)
            .then(() => { this.show = false; this.load() })
        }
    }
}
</script>