const params = new URLSearchParams(window.location.search);
const id = params.get("id");

$.ajax({
  url: "http://localhost/dallas-organic/server/blog/getItem/" + id,
  type: "GET",
  dataType: "json",
  success: function (blogs) {
    try {
      // Xử lý kết quả trả về từ REST API
      var blogHtml = "";
      var blog = blogs[0];
      blogHtml =
        blogHtml +
        `
                    <div class="section-title">
                        <p class="fs-5 fw-medium fst-italic text-primary">Blog hữu cơ</p>
                        <h1 class="display-6 mb-3">` +
        blog.title +
        `</h1>
                         <h5 class="mb-3">` +
        blog.subtitle +
        `</h5>
        <div class="blog-post-info">
  <span><i class="far fa-clock"></i>&nbsp;` +
        blog.min_read +
        ` min read&nbsp;&nbsp;&nbsp;&nbsp;</span>
  <span><i class="far fa-user"></i>&nbsp;Created by ` +
        blog.created_by +
        `&nbsp;&nbsp;&nbsp;&nbsp;</span>
  <span><i class="far fa-calendar"></i>&nbsp;Created at ` +
        blog.created_at +
        `</span>
</div>

                    </div>` +
        blog.content;

      // Hiển thị danh sách sản phẩm trên giao diện
      $("#blog-detail").html(blogHtml);
    } catch (e) {
      console.log("Error parsing JSON response:", e);
    }
  },
  error: function (xhr, status, error) {
    console.log("Error:", error);
  },
});
