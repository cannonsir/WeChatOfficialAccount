<template>
  <div>
    <div class="wrap">
      <div class="icons">
        <div class="item">
          <div class="content add" @click="addVisible = true"><i class="el-icon-plus">添加</i></div>
        </div>

        <div v-for="account in accounts" :key="account.id" class="item">
          <div class="content icon" @click="handleSelected(account)">
            <div class="avatar">
              <el-avatar shape="square" :src="account.avatar" />
            </div>
            <div class="title">{{ account.name || 'untitled' }}</div>
            <div v-if="account.type" class="desc">{{ account.type }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="drawer">
      <el-drawer
        title="绑定公众号"
        :visible.sync="addVisible"
        size="40%"
      >
        <div class="add-form">
          <el-form ref="ruleForm" :model="form" :rules="rules" label-width="105px" class="demo-ruleForm">
            <el-form-item label="名称" prop="name">
              <el-input v-model="form.name" />
            </el-form-item>
            <el-form-item label="头像" prop="avatar">
              <el-upload
                class="avatar-uploader"
                action="/api/files/upload"
                name="file"
                :headers="uploadHeaders"
                :show-file-list="false"
                :on-success="handleAvatarSuccess"
                :on-error="handleUploadError"
              >
                <img v-if="form.avatar" :src="form.avatar" class="avatar">
                <i v-else class="el-icon-plus avatar-uploader-icon" />
              </el-upload>
            </el-form-item>
            <el-form-item label="二维码" prop="qr_code">
              <el-upload
                class="avatar-uploader"
                action="/api/files/upload"
                name="file"
                :headers="uploadHeaders"
                :show-file-list="false"
                :on-success="handleQrSuccess"
                :on-error="handleUploadError"
              >
                <img v-if="form.qr_code" :src="form.qr_code" class="avatar">
                <i v-else class="el-icon-plus avatar-uploader-icon" />
              </el-upload>
            </el-form-item>
            <el-form-item label="类型" prop="type">
              <el-select v-model="form.type" clearable>
                <el-option v-for="type in types" :key="type" :value="type">{{ type }}</el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="APP_ID" prop="app_id">
              <el-input v-model="form.app_id" />
            </el-form-item>
            <el-form-item label="APP_SECRET" prop="app_secret">
              <el-input v-model="form.app_secret" />
            </el-form-item>
            <el-form-item label="接口回调地址" prop="callback_url">
              <el-input v-model="form.callback_url" />
            </el-form-item>
            <el-form-item label="接口回调token" prop="callback_token">
              <el-input v-model="form.callback_token" />
            </el-form-item>
            <el-form-item label="描述信息" prop="desc">
              <el-input v-model="form.desc" />
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="submitForm('ruleForm')">绑定</el-button>
              <el-button @click="resetForm('ruleForm')">重置</el-button>
            </el-form-item>
          </el-form>
        </div>
      </el-drawer>
    </div>
  </div>
</template>

<script>
import store from '@/store'
import { types } from '../../enums/account'

export default {
  name: 'AccountsList',
  data() {
    return {
      accounts: [],
      addVisible: false,
      form: {
        name: '',
        avatar: '',
        qr_code: '',
        type: '',
        desc: '',
        app_id: '',
        app_secret: '',
        callback_url: '',
        callback_token: ''
      },
      rules: {
        name: { required: true, message: '请填写公众号名称' },
        type: { required: true, message: '请选择类型' },
        avatar: { required: true, message: '请上传公众号头像' },
        app_id: { required: true, message: '请填写APP_ID' },
        app_secret: { required: true, message: '请填写APP_SECRET' }
      }
    }
  },
  computed: {
    types() {
      return types
    },
    uploadHeaders() {
      const header = {
        Accept: 'application/json'
      }

      if (store.getters.token.access_token) {
        header.Authorization = store.getters.token.access_token
      }

      return header
    }
  },
  created() {
    this.fetchAccounts()
  },
  methods: {
    async fetchAccounts() {
      const { data } = await this.$http.get('api/plugins/WeChatOfficialAccount/accounts')
      this.accounts = data
    },
    handleAdd() {
      this.$message.success('add')
    },
    handleSelected(account) {
      this.$message.success(account.name)
    },
    async submitForm() {
      try {
        await this.$http.post('api/plugins/WeChatOfficialAccount/accounts', this.form)
        await this.fetchAccounts()
        this.addVisible = false
        this.$message.success('添加成功')
      } catch (e) {
        this.$message.error('操作失败,请稍后再试')
      }
    },
    resetForm(formName) {
      this.$refs[formName].resetFields()
    },
    handleAvatarSuccess(response, file, fileList) {
      this.form.avatar = response.data.url
    },
    handleQrSuccess(response, file, fileList) {
      this.form.qr_code = response.data.url
    },
    handleUploadError(err, file, fileList) {
      this.$message.error('上传失败，请检查文件大小')
    }
  }
}
</script>

<style lang="scss" scoped>
  .wrap {
    /*display: flex;*/
    /*justify-content: center;*/
    /*align-items: center;*/
    margin: auto;
    .icons {
      display: flex;
      align-items: center;
      flex-shrink: 0;
      flex-wrap: wrap;
      .item {
        // 正方形
        width: 12.8%;
        // padding或者margin是已宽度为单位进行百分比取值的
        padding-bottom: 12.8%;
        margin: .7%;
        height: 0;
        cursor: pointer;
        position: relative;
        &:hover {
          box-shadow: 0 2px 8px rgba(0,0,0,.09)
        }
        .content {
          // 通过决定定位来撑起宽高
          position: absolute;
          display: flex;
          flex-direction: column;
          justify-content: center;
          align-items: center;
          top: 0;
          left: 0;
          height: 100%;
          width: 100%;
          overflow: hidden;
          border-radius: 2px;
        }
        .icon {
          border: 1px solid #e8e8e8;
          &:hover {
            border-color: rgba(0,0,0,.09);
          }
          .avatar {
            width: 70%;
            height: 70%;
            overflow: hidden;
            margin-bottom: 4%;
            display: flex;
            justify-content: center;
            align-items: center;
            .el-avatar {
              width:100%;
              height:100%;
              background: none;
            }
          }
          .title {
            font-size: 1.2rem;
            &:hover {
              color: #40a9ff;
            }
          }
          .desc {
            position: absolute;
            bottom: 0;
            font-size: .6rem;
            color: rgba(0,0,0,.45)
          }
        }
        .add {
          border: 1px dashed #d9d9d9;
          color: rgba(0,0,0,.45);
          &:hover {
            color: #40a9ff;
            border-color: #40a9ff;
          }
        }
      }
    }
  }

  .add-form {
    padding: 0 15px !important;
  }
</style>

<style>
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 120px;
    height: 120px;
    line-height: 120px;
    text-align: center;
  }
  .avatar {
    width: 120px;
    height: 120px;
    display: block;
  }
</style>
