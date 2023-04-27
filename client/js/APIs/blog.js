$.ajax({
  url: "http://localhost/dallas-organic/server/blog/getAllTitle?offset=0",
  type: "GET",
  dataType: "json",
  success: function (blogs) {
    try {
      // Xử lý kết quả trả về từ REST API
      var blogListHtml = "";
      $.each(blogs, function (index, blog) {
        blogListHtml =
          blogListHtml +
          `<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <img class="img-fluid" src="` +
          blog.image +
          `" alt="">
                    <div class="bg-light p-4">
                        <a class="d-block h5 lh-base mb-4" style="height: 60px;" href="blog-detail.html?id=` +
          blog.id +
          `">` +
          blog.title +
          `</a>
                        <div class="text-muted border-top pt-4">
                            <small class="me-3"><i class="fa fa-user text-primary me-2"></i>` +
          blog.created_by +
          `</small>
                            <small class="me-3"><i class="fa fa-calendar text-primary me-2"></i>` +
          blog.created_at +
          `</small>
                        </div>
                    </div>
                </div>`;
      });
      // Hiển thị danh sách sản phẩm trên giao diện
      $("#blog-list").html(blogListHtml);
    } catch (e) {
      console.log("Error parsing JSON response:", e);
    }
  },
  error: function (xhr, status, error) {
    console.log("Error:", error);
  },
});
