<template>
  <div class="container mt-4">


    <!-- Add Item Button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
  <h2>Items List</h2>
  <button class="btn btn-success" @click="openModal('add')">
    Add Item
  </button>
</div>

    <!-- Records Table -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>SI.No</th>
          <th>Name</th>
          <th>Description</th>
          <th>Code</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in items" :key="item.uuid">
          <td>{{ index + 1 }}</td>
          <td>{{ item.name }}</td>
          <td>{{ item.description }}</td>
          <td>{{ item.code }}</td>
          <td>{{ item.status }}</td>
          <td>
            <button class="btn btn-primary btn-sm" @click="openModal('edit', item)">
              Edit
            </button>
            <button class="btn btn-danger btn-sm" @click="deleteItem(item.uuid)">
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="itemModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ modalTitle }}</h5>
            <!-- Use @click to close modal programmatically -->
            <button type="button" class="btn-close" @click="modalInstance.hide()"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveItem">
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" v-model="currentItem.name" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" v-model="currentItem.description" required></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Code</label>
                <input type="text" class="form-control" v-model="currentItem.code" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-control" v-model="currentItem.status">
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
              <button type="submit" class="btn btn-success">Save</button>
              <!-- Cancel button now closes modal programmatically -->
              <button type="button" class="btn btn-secondary" @click="modalInstance.hide()">Cancel</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
// Import Bootstrap JS as a module
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js';

export default {
  name: 'Items',
  data() {
    return {
      items: [],
      currentItem: {},
      modalTitle: '',
      modalInstance: null
    };
  },
  mounted() {
    this.fetchItems();

    // Initialize Bootstrap 5 modal programmatically
    const modalEl = document.getElementById('itemModal');
    this.modalInstance = new bootstrap.Modal(modalEl, {
      backdrop: 'static',
      keyboard: false
    });
  },
  methods: {
    fetchItems() {
      axios.get('/api/items')
        .then(res => this.items = res.data)
        .catch(err => console.error(err));
    },
    openModal(mode, item = null) {
      if (!this.modalInstance) return; // safety check
      if (mode === 'add') {
        this.modalTitle = 'Add Item';
        this.currentItem = { name: '', description: '', code: '', status: 'active' };
      } else if (mode === 'edit') {
        this.modalTitle = 'Edit Item';
        this.currentItem = { ...item };
      }
      this.modalInstance.show();
    },
    saveItem() {
      if (this.currentItem.uuid) {
        axios.put(`/api/items/${this.currentItem.uuid}`, this.currentItem)
          .then(() => {
            this.fetchItems();
            this.modalInstance.hide();
          })
          .catch(err => console.error(err));
      } else {
        axios.post('/api/items', this.currentItem)
          .then(() => {
            this.fetchItems();
            this.modalInstance.hide();
          })
          .catch(err => console.error(err));
      }
    },
    deleteItem(uuid) {
      if (confirm('Are you sure you want to delete this item?')) {
        axios.delete(`/api/items/${uuid}`)
          .then(() => this.fetchItems())
          .catch(err => console.error(err));
      }
    }
  }
};
</script>

<style scoped>
h2 {
  color: #333;
}
</style>
