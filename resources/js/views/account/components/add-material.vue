<template>
  <div>
    <el-dialog
      :title="'添加' + typeLable"
      :visible="dialogVisible"
      :before-close="closeDialog"
      :close-on-click-modal="false"
      append-to-body
    >
      <ImgUpload v-if="params.type == 'image'" @data="onDataChange"></ImgUpload>
      <VideoUpload v-if="params.type == 'video'" @data="onDataChange"></VideoUpload>
      <Voice v-if="params.type == 'voice'" @data="onDataChange"></Voice>
      <News v-if="params.type == 'news'" @data="onDataChange"></News>

      <span slot="footer" class="dialog-footer">
        <el-button @click="closeDialog">取 消</el-button>
        <el-button type="primary" @click="handleAdd()">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { store } from "../../../api/material";
import { MaterialUploadUrl, MaterialLabel } from "../../../enums/material";
import ImgUpload from "./image";
import VideoUpload from "./video";
import Voice from "./voice";
import News from "./news";

import { Loading } from "element-ui";

export default {
  components: {
    ImgUpload,
    VideoUpload,
    Voice,
    News
  },
  reject: ['account'],
  props: {
    params: {
      type: Object,
      default: () => {
        return {
          type: "image"
        };
      }
    }
  },
  data() {
    return {
      dialogVisible: true,
      action: MaterialUploadUrl,
      data: {}
    };
  },
  computed: {
    typeLable() {
      return MaterialLabel[this.params.type] || "";
    }
  },
  methods: {
    closeDialog(refresh = false) {
      this.$emit("close-dialog", refresh);
    },
    handleAdd() {
      let data = {
        type: this.params.type,
        ...this.data
      };
      let options = {};
      let loadingInstance = Loading.service(options);
      this.$nextTick(() => {
        // 以服务的方式调用的 Loading 需要异步关闭
        loadingInstance.close();
      });

      store(this.account.id, data)
        .then(res => {
          this.$message.success("添加成功");
          this.closeDialog(true);
        })
        .catch(err => {
          console.log(err);
          this.$message.error("创建失败，请重试");
        });
    },
    onDataChange(data) {
      this.data = data;
    }
  }
};
</script>

<style lang="scss" scoped>
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
  border-color: #409eff;
}
.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  line-height: 178px;
  text-align: center;
}
.avatar {
  width: 178px;
  height: 178px;
  display: block;
}
</style>
