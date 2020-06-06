<template>
  <div class="sheet">
    <el-form ref="ruleForm" :model="form" :rules="rules" label-width="105px" class="demo-ruleForm">
      <el-form-item label="名称" prop="name">
        <el-input v-model="form.name" />
      </el-form-item>
      <el-form-item label="头像" prop="avatar">
        <el-upload
          class="avatar-uploader"
          action="/api/upload"
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
          action="/api/upload"
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
      <el-form-item label="EncodingAesKey" prop="encoding_aes_key">
        <el-input v-model="form.encoding_aes_key" />
      </el-form-item>
      <el-form-item label="描述信息" prop="desc">
        <el-input v-model="form.desc" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="submitForm('ruleForm')">确认</el-button>
        <el-button @click="resetForm">重置</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { types } from '../../enums/account'
// import store from '@/store'
import _ from 'lodash'

const resetFields = {
  name: '',
  avatar: '',
  qr_code: '',
  encoding_aes_key: '',
  type: '',
  desc: '',
  app_id: '',
  app_secret: '',
  callback_url: '',
  callback_token: ''
}

export default {
  name: 'AccountSheet',
  props: {
    value: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      form: resetFields,
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

      // if (store.getters.token.access_token) {
      //   header.Authorization = store.getters.token.access_token
      // }

      return header
    }
  },
  watch: {
    value: {
      immediate: true,
      deep: true,
      handler(value) {
        const copy = _.cloneDeep(value)

        for (const key in resetFields) {
          if (!copy.hasOwnProperty(key)) {
            copy[key] = resetFields[key]
          }
        }

        this.form = copy
      }
    }
  },
  methods: {
    async submitForm() {
      this.$refs.ruleForm.validate(valid => {
        if (!valid) return this.$message.error('请检查参数')

        this.$emit('submit', this.form)
      })
    },
    resetForm() {
      this.$refs.ruleForm.resetFields()
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
.avatar-uploader::v-deep .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.avatar-uploader::v-deep .el-upload:hover {
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
