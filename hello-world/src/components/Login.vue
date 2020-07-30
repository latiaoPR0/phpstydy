<template>
  <div>
    <div class="register-wrapper">
      <div id="register">
        <p class="title">登录</p>
        <el-form
          :model="ruleForm2"
          status-icon
          :rules="rules2"
          ref="ruleForm2"
          label-width="0"
          class="demo-ruleForm"
        >
          <el-form-item prop="username">
            <el-input
              type="text"
              v-model="ruleForm2.username"
              auto-complete="off"
              placeholder="请输入输入账号"
            ></el-input>
          </el-form-item>
          <el-form-item prop="password">
            <el-input
              type="password"
              v-model="ruleForm2.password"
              auto-complete="off"
              placeholder="请输入输入密码"
            ></el-input>
          </el-form-item>
          <el-form-item prop="code" class="code">
            <el-input v-model="ruleForm2.code" placeholder="请输入验证码"></el-input>
            <!-- 验证码图片 -->
            <el-col>
              <div class="captcha_code">
                <img src ref="code2" @click="changeCode" />
              </div>
            </el-col>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="submitForm('ruleForm2')" style="width:100%;">登录</el-button>
            <p class="login" @click="gotoLogin">没有账号？立即注册</p>
          </el-form-item>
        </el-form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "Register",
  mounted() {
    // 自动调用刷新验证码
    this.changeCode();
  },
  data() {
    //  <!--验证码是否为空-->
    let checkSmscode = (rule, value, callback) => {
      if (value === "") {
        callback(new Error("请输入验证码"));
      } else {
        callback();
      }
    };
    // <!--验证账户-->
    let validatePass = (rule, value, callback) => {
      if (value === "") {
        callback(new Error("请输入账户"));
      } else {
        // if (this.ruleForm2.checkPass !== "") {
        //   this.$refs.ruleForm2.validateField("checkPass");
        // }
        callback();
      }
    };
    // <!--二次验证密码-->
    let validatePass2 = (rule, value, callback) => {
      if (value === "") {
        callback(new Error("请再次输入密码"));
      } else {
        callback();
      }
    };
    return {
      ruleForm2: {
        uniqid: "",
        username: "",
        password: "",
        code: "",
        result: "",
      },
      rules2: {
        username: [{ validator: validatePass, trigger: "change" }],
        password: [{ validator: validatePass2, trigger: "change" }],
        code: [{ validator: checkSmscode, trigger: "change" }],
      },
      buttonText: "发送验证码",
      isDisabled: false, // 是否禁止点击发送验证码按钮
      flag: true,
    };
  },
  methods: {
    // <!--登录 formName event-->
    submitForm(formName, event) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          // setTimeout(() => {
          //   console.log(valid);
          //   alert("登录成功");
          // }, 400);

          // event.preventDefault();
          let formData = new FormData();
          //下面是表单绑定的data 数据
          formData.append("username", this.ruleForm2.username);
          formData.append("password", this.ruleForm2.password);
          formData.append("code", this.ruleForm2.code);
          formData.append("uniqid", this.ruleForm2.uniqid);

          //axios

          //根据后台接收参数格式进行修改
          let config = {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          };

          axios
            .post("http://adminapi.pyg.com/login", formData, config)
            // 后台传来的数据
            .then((res) => {
              this.ruleForm2.result = res;
              // console.log(this.ruleForm2.result.data.code);
              // console.log(this.ruleForm2.result.data);
              this.iflogin(this.ruleForm2.result);
            })
            .catch((err) => {
              console.log(err);
            });
        } else {
          console.log("error submit!!");
          return false;
        }
      });

      // var formData = new FormData(event.target);
      // console.log(formData);
    },
    iflogin: function (result) {
      if (result.data != undefined) {
        if (result.data.code == 200 && result.data.data != null) {
          console.log(result);
          this.$router.push({
            name: "index",
            params: { result: result },
          }); // 跳转登录页面
          return;
        }

        if (result.data.code == 402) {
          this.$confirm(result.data.msg, "提示", {})
            .then(() => {
              // sessionStorage.removeItem("user");
              this.$router.push("/login"); // 返回重新登录页面
            })
            .catch(() => {});
        } else {
          this.$confirm(this.ruleForm2.result.data.msg, "提示", {})
            .then(() => {
              // sessionStorage.removeItem("user");
              this.$router.push("/login"); // 返回重新登录页面
            })
            .catch(() => {});
        }
      }
    },

    // <!--进入登录页-->
    gotoLogin() {
      this.$router.push({
        path: "/login",
      });
    },

    changeCode() {
      var res = null;
      var uniqid = null;
      (res = axios({
        // 此处不写默认为get方法
        url: "http://adminapi.pyg.com/yzm",
        // method: "options",
      })),
        res.then((result) => {
          // console.log(result.data.data),
          (this.ruleForm2.uniqid = result.data.data.uniqid),
            // console.log(this.ruleForm2.uniqid);
            this.$refs.code2.setAttribute(
              "src",
              "http://adminapi.pyg.com/" + result.data.data.src
            );
        });
    },
  },
};
</script>

<style scoped>
.loading-wrapper {
  position: fixed;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  background: #aedff8;
  display: flex;
  align-items: center;
  justify-content: center;
}
.register-wrapper img {
  position: absolute;
  z-index: 1;
}
.register-wrapper {
  position: fixed;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
}
#register {
  max-width: 340px;
  margin: 60px auto;
  background: #fff;
  padding: 20px 40px;
  border-radius: 10px;
  position: relative;
  z-index: 9;
}
.title {
  font-size: 26px;
  line-height: 50px;
  font-weight: bold;
  margin: 10px;
  text-align: center;
}
.el-form-item {
  text-align: center;
}
.login {
  margin-top: 10px;
  font-size: 14px;
  line-height: 22px;
  color: #1ab2ff;
  cursor: pointer;
  text-align: left;
  text-indent: 8px;
  width: 160px;
}
.login:hover {
  color: #2c2fd6;
}
.code >>> .el-form-item__content {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.el-button--primary:focus {
  background: #409eff;
  border-color: #409eff;
  color: #fff;
}

.captcha_code {
  display: flex;
  padding-left: 10px;
  width: 140px;
  height: 40px;
}
.captcha_code > img {
  display: flex;
  width: 50%;
  height: 40px;
}
</style>
