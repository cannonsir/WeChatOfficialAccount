<template>
  <div>
    <meta name="referrer" content="never" />
    <el-tabs v-model="activeName" @tab-click="handleClick">
      <el-tab-pane v-for="(item,index) in tabs" :label="item.label" :name="item.name" :key="index">
        <el-row>
          <el-col :span="8">
            <div>
              <span style="font-size:20px;padding;20px">素材数量:</span>
              {{total_conut}}
            </div>
          </el-col>
          <el-col :span="8">
            <dir></dir>
          </el-col>
          <el-col :span="8" class="add_material">
            <el-button type="success" @click="onClickAddBtn(item.name)">添加素材</el-button>
          </el-col>
        </el-row>
        <el-row>
          <div
            class="tabs-box"
            v-loading.lock="loading"
            lement-loading-background="rgba(0, 0, 0, 0.8)"
          >
            <div
              class="item"
              v-for="(sitem ,sindex) in materialData(item.name)"
              :key="sindex"
              @click="onClickMaterial(item,sitem)"
            >
              <template v-if="item.name !== 'news'">
                <el-card class="box-card" shadow="hover">
                  <template v-if="item.name == 'video'">
                    <el-image :src="sitem.cover_url"></el-image>
                  </template>

                  <template v-if="item.name == 'image'">
                    <el-image :src="sitem.url"></el-image>
                  </template>

                  <template v-if="item.name == 'voice'">
                    <el-image :src="voiceIcon"></el-image>
                  </template>

                  <div class="bottom">
                    <el-tooltip effect="dark" :content="sitem.name" placement="top">
                      <div class="title">{{sitem.name}}</div>
                    </el-tooltip>

                    <div class="action-icon">
                      <i
                        class="el-icon-delete"
                        @click="onClickDel(sitem.media_id,item.name,sindex)"
                      ></i>
                    </div>
                  </div>
                </el-card>
              </template>
              <!-- 图文素材 -->
              <template v-else>
                <el-card class="box-card news" shadow="hover">
                  <div v-for="(nitem,index) in sitem.content.news_item" :key="index">
                    <div class="title">
                      <span>{{nitem.title}}</span>
                    </div>
                    <el-image :src="nitem.thumb_url"></el-image>
                    <div>作者: {{nitem.author}}</div>
                    <div class="action-icon">
                      <div class="link">
                        <el-link target="_blank" :href="nitem.url" type="primary">查看内容</el-link>
                      </div>
                      <i
                        class="el-icon-delete"
                        @click="onClickDel(sitem.media_id,item.name,sindex)"
                      ></i>
                    </div>
                  </div>
                  <!-- <el-image :src="item.url"></el-image> -->
                  <!-- <div class="bottom">
                    <span>{{item.name}}</span>
                    <span class="action-icon">
                      <i class="el-icon-edit"></i>
                      <i class="el-icon-delete"></i>
                    </span>
                  </div>-->
                </el-card>
              </template>
            </div>
            <div v-if="!materialData(item.name) || materialData(item.name).length == 0">暂无素材</div>
          </div>
        </el-row>
        <el-pagination
          hide-on-single-page
          background
          layout="prev, pager, next"
          @current-change="currentChange"
          :total="total_conut"
        ></el-pagination>
      </el-tab-pane>
    </el-tabs>

    <AddMaterial
      v-if="dialogNmae == 'AddMaterial'"
      @close-dialog="closeDialog"
      :params="dialogParams"
    ></AddMaterial>
  </div>
</template>

<script>
import { index, destroy } from "../../api/material"
import AddMaterial from "./components/add-material"

export default {
  inject: ['account'],
  components: {
    AddMaterial
  },
  data() {
    return {
      activeName: "image",
      dialogNmae: "",
      dialogParams: {},
      total_conut: 0,
      page_size: 10,
      loading: false,
      voiceIcon:
        "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFQAAABUCAMAAAArteDzAAAAaVBMVEUAAAAarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRkarRlIa6J1AAAAInRSTlMA9wYa38QR7ZJnMK1IIqBsO3fXDbSGQudZz5fKpV0rfbpRlHIjYQAAA35JREFUWMPFWduyqjAMDS0tgtwEFBGv/P9Hntmh3cWDTYsMs/Oio3SRy0qapuCU7PXIRdUGQxCFncgfrwzWCb/l4TCTML/xbxFlIQariEJ+AZnkwUBKkCdLIZvBQ5olsPw61Uhc4vTOa4Ca39P4IqYWXH2dyw5mWXUs2ez/8liZVx6YD2bW6wXRzmpesov0U70HxW5azTBmpD1xqJW9uUzfaS0Lp1ms0Nru6Nfv9WPSi8lahT2BKoWyvARPKZUPhLRiduq9ckHaKds6y5pa6XmARXJQutaEP4MzLJTzyJfmk193I2YKiyUdUXcf+OnCdKPO+JqNvxO2kx4YNcr+c2jvjpE7Wv27W4uRS/C1jFEu3mpdhJyX34PWISY3ByNj/SxhhZRjfZ0UMkUJt3Bxx08rJU2xbFB16YEZDiG3JSy6sHlXNPbCHIbOVpHiN1VzjBLzKOCkmxjGKld6B4oNbjkiqi3rkJeBNN8jBj7SUEaxyGgnjE1OkS0mHkUAgd5X/qWF80mWR7PaOY0410GrnHHXVHpSqlZII521RzeXqtpkTkgEEitIiwF1YeLDJgQnIldbgAx5wMBj5z4br+aWB5GdGbxUxGjUp6ESLmxhJsaMFzx+Pi5+VIpN6bTUlcvPfw/InXlvjO5MjsdE/ucg6DjxRlEJY4Wb0J1IlnR0ZoXGEHF/6l1I68d+vj3ho9xH0mO+cjumNiMxvg/tTOWYcIAkqCl+XjRbtH7CHv4aCQrIQIui3TCxNPyN1BMXfhQFFxCgJ/yzmYAaTpGgEZpPoOq60GJctfkRaX5IBApRVTNTm/TvnYHqCEoh6kMzUCuNxnUUpVzkB/2+/Pc5iTpT5PdNUx78FrMT6kymqbugmEpxNZU4JXaph7v0GbOGxJQ3SZU+ryINSWT8iAt6skg7txPD1wCJN/rrQG0nZuNzo54nHQOnNj6zRTtRj5Pe5klu0d7NBGTThvFENhNE20NQS5BtD9GgUdQqyQZtaSuZ4bIr1fUGcmHTCz1SRpJNL9GeE3xNHe35/CDhRj04DhLzI48b9eI48mxxONvyGLn+wGtsLTY5mm87RFg/7jhNxh3bD2aANWtHSFsOu7Yfy60fIG4/6lw/lN14fOwedJdWXxKD7m1H8u7LAwZMZsn88mCDa46/v5DZ6OoIhcf7dg7Y7mPalb7XcVEwDEFU+V3H/QOplcP+ctPpgwAAAABJRU5ErkJggg==",
      tabs: [
        {
          label: "图片",
          name: "image"
        },
        {
          label: "视频",
          name: "video"
        },
        {
          label: "语音",
          name: "voice"
        },
        {
          label: "图文消息",
          name: "news"
        }
      ],
      material: {
        image: {
          page: 1,
          list: []
        },
        video: {
          page: 1,
          list: []
        },
        voice: {
          page: 1,
          list: []
        },
        news: {
          page: 1,
          list: []
        }
      }
    };
  },
  created() {
    this.getData("image");
  },
  methods: {
    handleClick(tab) {
      this.loading = true;
      this.getData(tab.name);
    },
    getData(type) {
      // if (this.material[type].list.length != 0) {
      //   return;
      // }
      type = type || this.activeName;
      index(this.account.id, {
        type: type,
        page: this.material[type].page,
        pageSize: this.page_size
      })
        .then(data => {
          this.loading = false;
          this.material[type].list = data.item;
          this.total_conut = data.total_count || 0;
          if (data.errmsg) {
            this.$message.error(data.errmsg);
          }
        })
        .catch(err => {
          console.error(err)
          this.loading = false;
          this.$message.error("网络异常或公众号未设置正确");
        });
    },
    // 点击添加素材按钮
    onClickAddBtn(type) {
      this.dialogNmae = "AddMaterial";
      this.dialogParams = {
        type
      };
    },
    closeDialog(refresh) {
      if (refresh) {
        this.getData(this.activeName);
      }
      this.dialogNmae = "";
      this.dialogParams = {};
    },
    materialData(type) {
      return this.material[type] ? this.material[type].list : [];
    },
    onClickDel(mediaId, type, index) {
      this.$confirm("是否确认删除素材?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          destroy(mediaId, {
            appid: this.appid,
            type: type
          }).then(res => {
            this.material[type].list.splice(index, 1);
            this.$message({
              type: "success",
              message: "删除成功!"
            });
          });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消"
          });
        });
    },
    currentChange(page) {
      this.loading = true;
      this.material[this.activeName].page = page;
      this.getData();
    },
    onClickMaterial(type, item) {
      this.$emit("click", type, item);
    }
  }
}
</script>

<style lang="scss" scoped>
  .add_material {
    text-align: right;
  }
  .tabs-box {
    margin-top: 10px;
    display: flex;
    flex-wrap: wrap;
    .item {
      margin: 10px;
      .box-card {
        width: 200px;
        .bottom {
          margin-top: 20px;
          display: flex;
          .title {
            width: 80%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
          }
          .action-icon {
            margin-left: 10px;
            i {
              display: inline-block;
              margin-left: 10px;
            }
          }
        }
        .el-image {
          width: 150px;
          height: 150px;
        }
      }
      .news {
        .title {
          text-align: center;
          width: 100%;
          overflow: hidden;
          text-overflow: ellipsis;
          white-space: nowrap;
          padding-bottom: 10px;
        }
        .action-icon {
          .link {
            display: inline-block;
            width: 80%;
          }
          i {
            display: inline-block;
            margin-left: 10px;
          }
        }
      }
    }
  }
</style>
