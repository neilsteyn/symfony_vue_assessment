<template>
  <div class="filters-container">
    <h1>Filters</h1>
    <div class="form-control" ref="search">
      <span></span>
      <input
        type="text"
        v-model="search"
        v-on:input="onSearchInput()"
        v-on:keyup="onSearchKeyup()"
        v-on:keydown="onSearchKeydown()"
        placeholder="Search"
      />
    </div>
    <div class="form-control" ref="gender">
      <label>Gender</label>
      <input hidden="true" v-model="gender" />
      <div class="dropdown" ref="dropdown" v-on:click="toggleDropdown($event)">
        <p class="dd-placeholder">{{ gender ? gender : "All" }}</p>
        <div class="content">
          <ul>
            <li class="active" v-on:click="setGender('All', $event)">
              <a>All</a>
            </li>
            <li v-on:click="setGender('Male', $event)"><a>Male</a></li>
            <li v-on:click="setGender('Female', $event)"><a>Female</a></li>
            <li v-on:click="setGender('Trans', $event)"><a>Trans</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "contact-list-filters",
  data: function () {
    return {
      search: "",
      gender: "",
      typingTimer: 0,
      doneTypingInterval: 5000,
    };
  },
  methods: {
    toggleDropdown: function (e) {
      e.stopPropagation();
      e.preventDefault();

      // let dd = e.target.parentElement;
      let dd = this.$refs.dropdown;
      if (dd.classList.contains("show")) {
        dd.classList.remove("show");
        dd.classList.add("hide");
      } else {
        dd.classList.remove("hide");
        dd.classList.add("show");
      }
    },
    setGender: function (gender, e) {
      e.stopPropagation();
      e.preventDefault();

      document.querySelectorAll(".dropdown li").forEach(function (el, i, p) {
        el.classList.remove("active");
      });

      e.target.classList.add("active");

      this.gender = gender;

      // let dd = document.querySelector(".dropdown");
      let dd = this.$refs.dropdown;
      dd.classList.remove("show");
      dd.classList.add("hide");

      this.$refs.gender.classList.remove("error");

      this.applyFilters();
    },
    onSearchInput: function () {
      console.log(this.search);
    },
    onGenderSelect: function () {
      this.gender;
    },
    onSearchKeyup: function () {
      clearTimeout(this.typingTimer);
      this.typingTimer = setTimeout(
        this.applyFilters(),
        this.doneTypingInterval
      );
    },
    onSearchKeydown: function () {
      clearTimeout(this.typingTimer);
    },
    applyFilters: function () {
      (async () => {
        try {
          const res = await this.$http.get("contact-list/ajax", {
            params: {
              s: this.search,
              g: this.gender,
            },
          });

          document
            .querySelectorAll(".container table tr:not(:first-child)")
            .forEach(function (el, i, p) {
              el.remove();
            });

          let th = document
            .querySelector(".container table tr:first-child")
            .insertAdjacentHTML("afterend", res.data);
        } catch (e) {
          console.log(e);
        }
      })();
    },
  },
};
</script>

<style scoped>
.filters-container {
  float: left;
  width: 25%;
  box-shadow: 0 1px 20px #a6a6a4;
  padding: 0px 20px 20px 20px;
}

.dropdown {
  position: relative;
  margin: 10px 0 0 0;
}

.form-control {
  margin: 20px 0 20px 0;
}
.form-control input {
  padding: 10px;
  width: 100%;
}

.content {
  display: none;
  position: absolute;
  width: 100%;
  background-color: #fff;
  box-shadow: 0px 0px 2px 0px #0070ff;
  padding-bottom: 20px;
}

.content ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.content ul li {
  border: 1px solid;
  padding: 10px;
  padding-left: 20px;
  border: none;
  color: gray;
}

.content ul li:hover {
  cursor: pointer;
  background-color: rgba(91, 104, 235, 0.11);
}

.dropdown.hide .content {
  display: none;
}

.dropdown.show .content {
  display: block;
}

.dd-placeholder {
  border: 1px solid;
  padding: 10px;
  margin: 0;
  font-size: 13px;
  color: #6a6a6a;
}
.active {
  background-color: rgba(91, 104, 235, 0.11);
}
</style>