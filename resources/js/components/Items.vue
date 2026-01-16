<template>
  <div class="page-wrapper">
    <div class="container py-4">

      <!-- Header -->
      <div class="card shadow-sm mb-4 header-card">
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="mb-0 text-white fw-bold">📦 Items Management</h2>
          <button class="btn btn-light fw-semibold" @click="openModal('add')">
            ➕ Add Item
          </button>
        </div>
      </div>

      <!-- Table Card -->
      <div class="card shadow-sm">
        <div class="card-body p-0">
          <table class="table table-hover mb-0">
            <thead class="table-head">
              <tr>
                <th>SI.No</th>
                <th>Name</th>
                <th>Description</th>
                <th>Code</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in items" :key="item.uuid">
                <td>{{ index + 1 }}</td>
                <td class="fw-semibold">{{ item.name }}</td>
                <td>{{ item.description }}</td>
                <td>
                  <span class="badge bg-info text-dark">{{ item.code }}</span>
                </td>
                <td>
                  <span
                    class="badge"
                    :class="item.status === 'active' ? 'bg-success' : 'bg-secondary'"
                  >
                    {{ item.status }}
                  </span>
                </td>
                <td class="text-center">
                  <button
                    class="btn btn-sm btn-outline-primary me-2"
                    @click="openModal('edit', item)"
                  >
                    ✏️ Edit
                  </button>
                  <button
                    class="btn btn-sm btn-outline-danger"
                    @click="deleteItem(item.uuid)"
                  >
                    🗑 Delete
                  </button>
                </td>
              </tr>

              <tr v-if="items.length === 0">
                <td colspan="6" class="text-center text-muted py-4">
                  No records found
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="itemModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content rounded-4">
            <div class="modal-header modal-header-custom">
              <h5 class="modal-title text-white">{{ modalTitle }}</h5>
              <button class="btn-close btn-close-white" @click="modalInstance.hide()"></button>
            </div>

            <div class="modal-body">
              <form @submit.prevent="saveItem">
                <div class="mb-3">
                  <label class="form-label fw-semibold">Name</label>
                  <input type="text" class="form-control" v-model="currentItem.name" required>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-semibold">Description</label>
                  <textarea class="form-control" v-model="currentItem.description" required />
                </div>

                <div class="mb-3">
                  <label class="form-label fw-semibold">Code</label>
                  <input type="text" class="form-control" v-model="currentItem.code" required>
                </div>

                <div class="mb-4">
                  <label class="form-label fw-semibold">Status</label>
                  <select class="form-select" v-model="currentItem.status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                  </select>
                </div>

                <div class="text-end">
                  <button type="button" class="btn btn-secondary me-2" @click="modalInstance.hide()">
                    Cancel
                  </button>
                  <button type="submit" class="btn btn-success px-4">
                    Save
                  </button>
                </div>
              </form>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import axios from 'axios'
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js'

export default {
  name: 'Items',
  data() {
    return {
      items: [],
      currentItem: {},
      modalTitle: '',
      modalInstance: null
    }
  },
  mounted() {
    this.fetchItems()
    this.modalInstance = new bootstrap.Modal(
      document.getElementById('itemModal'),
      { backdrop: 'static', keyboard: false }
    )
  },
  methods: {
    fetchItems() {
      axios.get('/api/items').then(res => {
        this.items = res.data
      })
    },
    openModal(mode, item = null) {
      if (mode === 'add') {
        this.modalTitle = 'Add Item'
        this.currentItem = { name: '', description: '', code: '', status: 'active' }
      } else {
        this.modalTitle = 'Edit Item'
        this.currentItem = { ...item }
      }
      this.modalInstance.show()
    },
    saveItem() {
      const req = this.currentItem.uuid
        ? axios.put(`/api/items/${this.currentItem.uuid}`, this.currentItem)
        : axios.post('/api/items', this.currentItem)

      req.then(() => {
        this.fetchItems()
        this.modalInstance.hide()
      })
    },
    deleteItem(uuid) {
      if (confirm('Are you sure?')) {
        axios.delete(`/api/items/${uuid}`).then(() => this.fetchItems())
      }
    }
  }
}
</script>

<style scoped>
.page-wrapper {
  min-height: 100vh;
  background: linear-gradient(135deg, #eef2ff, #f8fafc);
}

.header-card {
  background: linear-gradient(135deg, #4f46e5, #6366f1);
  border-radius: 16px;
  padding: 20px;
}

.table-head {
  background-color: #f1f5f9;
}

.table th {
  font-weight: 600;
}

.modal-header-custom {
  background: linear-gradient(135deg, #4f46e5, #6366f1);
}

.card {
  border-radius: 16px;
}

.btn {
  border-radius: 20px;
}
</style>
