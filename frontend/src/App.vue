<template>
  <div id="app" class="container">
    <div>
      <h1>Users</h1>
      <hr />
      <template>
        <b-container fluid>
          <!-- User Interface controls -->
          <b-row>
            <b-col lg="6" md="3" class="my-1">
              <b-form-group
                label="Filter"
                label-cols-sm="1"
                label-align-sm="left"
                label-size="sm"
                label-for="filterInput"
                class="mb-0"
              >
                <b-input-group size="sm">
                  <b-form-input
                    v-model="filter"
                    type="search"
                    id="filterInput"
                    placeholder="Type to Search"
                  ></b-form-input>
                  <b-input-group-append>
                    <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                  </b-input-group-append>
                </b-input-group>
              </b-form-group>
            </b-col>
          </b-row>

          <!-- Main table element -->
          <b-table
            show-empty
            small
            stacked="md"
            :items="users"
            :fields="fields"
            :current-page="currentPage"
            :per-page="perPage"
            :filter="filter"
            :filterIncludedFields="filterOn"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :sort-direction="sortDirection"
            @filtered="onFiltered"
          >
            <template v-slot:cell(user_type_name)="row">
              <b-button
                size="sm"
                @click="openChangeUserType(row.item, row.index, $event.target)"
                class="mr-1"
              >{{row.item.user_type_name}}</b-button>
            </template>
          </b-table>

          <b-row>
            <b-col sm="5" md="3" class="my-1">
              <b-form-group
                label="Per page"
                label-cols-sm="6"
                label-cols-md="4"
                label-cols-lg="3"
                label-align-sm="right"
                label-size="sm"
                label-for="perPageSelect"
                class="mb-0"
              >
                <b-form-select
                  v-model="perPage"
                  id="perPageSelect"
                  size="sm"
                  :options="pageOptions"
                ></b-form-select>
              </b-form-group>
            </b-col>

            <b-col sm="0" md="6" class="my-1"></b-col>

            <b-col sm="7" md="3" class="my-1">
              <b-pagination
                v-model="currentPage"
                :total-rows="totalRows"
                :per-page="perPage"
                align="fill"
                size="sm"
                class="my-0"
              ></b-pagination>
            </b-col>
          </b-row>

          <b-modal
            :id="changeUserTypeModal.id"
            :title="changeUserTypeModal.title"
            ok-title="Update"
            @ok="updateUserType"
          >
            <pre>{{ selectedUserId }}</pre>
            <pre>{{ selectedUserTypeId }}</pre>
            <select @change="updateSelectedUserType">
              <option
                v-for="user_type in user_types"
                :value="user_type.id"
                :key="user_type.id"
                :selected="selectedUserTypeName != undefined && selectedUserTypeName == user_type.name ? 'selected' : ''"
              >{{ user_type.name }}</option>
            </select>
          </b-modal>
        </b-container>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      fields: [
        {
          key: "full_name",
          label: "Full name",
          sortable: true,
          sortDirection: "desc"
        },
        {
          key: "email",
          label: "E-mail",
          sortable: true
        },
        {
          key: "login_time",
          label: "Last Logged In",
          sortable: true
        },
        {
          key: "authorized_on",
          label: "Authorized",
          sortable: true
        },
        {
          key: "user_type_name",
          label: "User Type",
          sortable: true
        }
      ],
      users: [],
      user_types: [],
      totalRows: 1,
      currentPage: 1,
      perPage: 5,
      pageOptions: [5, 10, 15],
      sortBy: "",
      sortDesc: false,
      sortDirection: "asc",
      filter: null,
      filterOn: [],
      changeUserTypeModal: {
        id: "change-user-type-modal",
        title: "",
        content: ""
      },
      selectedUserId: 0,
      selectedUserTypeId: 0,
      selectedUserTypeName: ''
    };
  },

  created: function() {
    this.fetchUsers();
    this.fetchUserTypes();
  },

  methods: {
    updateSelectedUserType(val) {
      this.selectedUserTypeId = val.target.selectedOptions[0].value;
    },
    fetchUsers() {
      let uri = "http://localhost/backend/public/api/users";
      this.axios.get(uri).then(response => {
        this.users = response.data;
      });
    },
    fetchUserTypes() {
      let uri = "http://localhost/backend/public/api/user_types";
      this.axios.get(uri).then(response => {
        this.user_types = response.data.data;
      });
    },
    async updateUserType() {
      let uri = "http://localhost/backend/public/api/user/update";
      let data = {
        user_id: this.selectedUserId,
        user_type_id: this.selectedUserTypeId
      };
      await this.axios.post(uri, data);
      this.fetchUsers();
    },
    openChangeUserType(item, index, button) {
      this.changeUserTypeModal.title = `Confirmation`;
      this.selectedUserId = item.id; //JSON.stringify(item, null, 2);
      this.selectedUserTypeName = item.user_type_name;
      this.$root.$emit("bv::show::modal", this.changeUserTypeModal.id, button);
    },
    hideModal() {
      this.$refs[this.changeUserTypeModal.id].hide();
    },
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    }
  },
  computed: {
    sortOptions() {
      // Create an options list from our fields
      return this.fields
        .filter(f => f.sortable)
        .map(f => {
          return { text: f.label, value: f.key };
        });
    }
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.users.length;
  }
};
</script>

<style>
</style>
