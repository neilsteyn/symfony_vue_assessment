<template>
  <div class="container">
    <div class="content-left">
      <h1>Get in touch</h1>
    </div>
    <div class="content-right">
      <p class="success-msg" v-if="success">
        Thank you for contacting us, we will be in touch soon !
      </p>
      <form v-if="!success">
        <div class="form-control" ref="name">
          <label for=""></label>
          <input
            v-model="name"
            v-on:input="onNameInput()"
            type="text"
            placeholder="Name"
          />
          <div class="error"></div>
        </div>
        <div class="form-control" ref="email">
          <label for=""></label>
          <input
            v-model="email"
            v-on:input="onEmailInput()"
            type="text"
            placeholder="Email"
          />
          <div class="error"></div>
        </div>
        <div class="form-control" ref="gender">
          <input hidden="true" v-model="gender" />
          <div
            class="dropdown"
            ref="dropdown"
            v-on:click="toggleDropdown($event)"
          >
            <p class="dd-placeholder">{{ gender ? gender : "Gender" }}</p>
            <div class="content">
              <ul>
                <li v-on:click="setGender('Male', $event)"><a>Male</a></li>
                <li v-on:click="setGender('Female', $event)"><a>Female</a></li>
                <li v-on:click="setGender('Trans', $event)"><a>Trans</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="form-control" ref="content">
          <label for=""></label>
          <textarea
            v-model="content"
            v-on:input="onContentInput()"
            placeholder="Content"
          ></textarea>
          <div class="error"></div>
        </div>

        <a class="btn submit" v-on:click="onSubmit()">Send</a>
      </form>
    </div>
    <div class="clearfix"></div>
  </div>
</template>

<script>
export default {
  name: "contact-form",
  data: function () {
    return {
      name: "",
      email: "",
      gender: "",
      content: "",
      success: false,
    };
  },
  methods: {
    onSubmit: function () {
      (async () => {
        if (!this.name) {
          this.$refs.name.classList.add("error");
        }
        if (!this.email) {
          this.$refs.email.classList.add("error");
        }
        else {
          try {
            const res = await this.$http.get("contact/validate-email?e="+this.email);

            if (!res.data.is_valid) {
              this.$refs.email.classList.add("error");
              return;
            }
          } catch (e) {
            console.log(e);
          }
        }
        if (!this.gender) {
          this.$refs.gender.classList.add("error");
        }
        if (!this.content) {
          this.$refs.content.classList.add("error");
        }

        if (
          this.$refs.name.classList.contains("error") ||
          this.$refs.email.classList.contains("error") ||
          this.$refs.gender.classList.contains("error") ||
          this.$refs.content.classList.contains("error")
        ) {
          return;
        }

        try {
          const res = await this.$http.post("contact/create", {
            name: this.name,
            email: this.email,
            gender: this.gender,
            content: this.content,
          });

          if (res.data.success) {
            this.success = true;
          }
        } catch (e) {
          console.log(e);
        }
      })();
    },
    setGender: function (gender, e) {
      e.stopPropagation();
      e.preventDefault();

      document.querySelectorAll(".dropdown li").forEach(function (el, i, p) {
        el.classList.remove("active");
      });

      e.target.classList.add("active");

      this.gender = gender;

      let dd = this.$refs.dropdown;
      dd.classList.remove("show");
      dd.classList.add("hide");

      this.$refs.gender.classList.remove("error");
    },
    toggleDropdown: function (e) {
      e.stopPropagation();
      e.preventDefault();

      let dd = this.$refs.dropdown;
      if (dd.classList.contains("show")) {
        dd.classList.remove("show");
        dd.classList.add("hide");
      } else {
        dd.classList.remove("hide");
        dd.classList.add("show");
      }
    },

    onNameInput: function () {
      this.$refs.name.classList.remove("error");
    },
    onEmailInput: function () {
      this.$refs.email.classList.remove("error");
    },
    onContentInput: function () {
      this.$refs.content.classList.remove("error");
    },
  },
};
</script>

<style scoped>
.container {
  position: relative;
  margin-top: 90px;
  margin-left: auto;
  margin-right: auto;
  width: 800px;
  box-shadow: 0px 0px 2px 0px #0070ff;
  border-radius: 23px;
  overflow: hidden;
}

.content-left {
  float: left;
  width: 50%;
  background: linear-gradient(
    105deg,
    rgba(91, 104, 235, 1) 0%,
    rgba(40, 225, 253, 1) 100%
  );
  text-align: center;
  color: #fff;
  height: 395px;
  height: 485px;
}

.content-left h1 {
  margin: 0;
  margin-top: 50%;
  transform: translateY(-50%);
}

.content-right {
  float: right;
  width: 50%;
  padding: 50px;
}

.clearfix {
  clear: both;
}

.form-control {
  margin-bottom: 25px;
}

.form-control input {
  width: 100%;
  padding: 10px;
}

.form-control textarea {
  width: 100%;
  height: 125px;
  padding: 10px;
  resize: none;
}

.btn {
  border-radius: 23px;
  font-weight: 500;
  letter-spacing: 0.3px;
}

.submit {
  float: right;
  background: linear-gradient(
    105deg,
    rgba(91, 104, 235, 1) 0%,
    rgba(40, 225, 253, 1) 100%
  );
  padding: 10px 20px;
  color: #fff;
}

.submit:hover {
  cursor: pointer;
}

/** drop down styles */
.dropdown {
  position: relative;
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

.form-control.error input,
.form-control.error textarea,
.form-control.error .dropdown {
  border: 1px solid red;
}

.success-msg {
  text-align: center;
  margin-top: 50%;
  transform: translateY(-50%);
}
</style>