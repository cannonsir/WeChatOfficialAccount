<template>
  <div>
    <el-form ref="form" :model="form" label-width="80px">
      <el-form-item label="标题">
        <el-input v-model="form.title"></el-input>
      </el-form-item>
      <el-form-item label="作者">
        <el-input v-model="form.author"></el-input>
      </el-form-item>
      <el-form-item label="摘要">
        <el-input v-model="form.digest"></el-input>
      </el-form-item>
      <el-form-item label="原文链接">
        <el-input v-model="form.source_url"></el-input>
      </el-form-item>
      <el-form-item label="启用封面">
        <el-switch v-model="form.show_cover" active-color="#13ce66" inactive-color="#ff4949"></el-switch>
      </el-form-item>
      <el-form-item label="封面">
        <el-upload
          class="avatar-uploader"
          :action="action"
          :show-file-list="false"
          :on-success="handleAvatarSuccess"
          :before-upload="beforeAvatarUpload"
        >
          <img v-if="imageUrl" :src="imageUrl" class="avatar" />
          <i v-else class="el-icon-plus avatar-uploader-icon"></i>
        </el-upload>
      </el-form-item>
      <el-form-item label="文章内容">
        <!-- 可以用项目中的富文本编辑器替换 -->
        <el-input type="textarea" v-model="form.content"></el-input>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { store } from "../../../api/material";
import { MaterialUploadUrl } from "../../../enums/material";

export default {
  inject: ["account"],
  data() {
    return {
      action: MaterialUploadUrl,
      imageUrl: "",
      form: {
        title: "",
        author: "",
        digest: "",
        source_url: "",
        show_cover: true,
        thumb_media_id: "",
        content: ""
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
    handleAvatarSuccess(res, file) {
      this.imageUrl = res.data.url;
      store(this.account.id, {
        url: this.imageUrl,
        type: "thumb"
      })
        .then(data => {
          this.form.thumb_media_id = data.media_id;
          this.$message.success("封面上传成功");
        })
        .catch(err => {
          this.$message.error("封面上传失败,请重试");
          this.media_id = "";
        });
    },
    beforeAvatarUpload(file) {
      const isLt2M = file.size / 1024 / 1024 < 2;
      if (!isLt2M) {
        this.$message.error("上传图片大小不能超过 2MB!");
      }
      return isLt2M;
    }
  }
};
</script>

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
