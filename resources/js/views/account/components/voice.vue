<template>
  <div>
    <el-form ref="form" :model="form" label-width="80px">
      <el-form-item label="音频文件">
        <el-upload
          class="upload-demo"
          :action="action"
          :on-remove="handleRemove"
          :before-remove="beforeRemove"
          :limit="1"
          :on-success="handleSuccess"
          :file-list="fileList"
        >
          <el-button size="small" type="primary">点击上传</el-button>
          <div slot="tip" class="el-upload__tip">只能上传mp3</div>
        </el-upload>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { MaterialUploadUrl} from "../../../enums/material";

export default {
  data() {
    return {
      action: MaterialUploadUrl + "?strategy=voice",
      fileList: [],
      form: {
        title: "",
        introduction: "",
        url: ""
      }
    };
  },
  watch: {
    form: {
      handler(val) {
        this.$emit("data", val);
      },
      deep: true
    }
  },
  methods: {
    closeDialog(refresh = false) {
      this.$emit("close-dialog", refresh);
    },
    handleRemove(file, fileList) {
      this.form.url = "";
    },
    handleSuccess(res, file) {
      this.form.url = res.data.url;
      if (this.title == "") {
        this.form.title = res.data.originalName;
      }
    },
    beforeRemove(file, fileList) {
      return this.$confirm(`确定移除 ${file.name}？`);
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
