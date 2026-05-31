</main>
<footer style="text-align: center; padding: 25px; background-color: #0b132b; color: #94a3b8; font-size: 14px; margin-top: 50px; border-top: 3px solid #c7a668;" class="px-4">
    <div class="container-fluid text-center">
        <p style="margin: 0; font-weight: 500; letter-spacing: 0.6px;">&copy; 2026 Balwil Grand Hotel. All Rights Reserved.</p>
    </div>
</footer>

<script>
    $(document).ready(function() {
                $('#summernote').summernote({
                        callbacks: {
                            onImageUpload: function(files) {
                                for (let i = 0; i < files.length; i++) {
                                    $.upload(files[i]);
                                }
                            }
                        },\
                        height: 200,\
                            toolbar: [\
                                ["style", ["bold", "italic", "underline", "clear"]],\
                                ["fontname", ["fontname"]],\
                                ["fontsize", ["fontsize"]],\
                                ["color", ["color"]],\
                                ["para", ["ul", "ol", "paragraph"]],\
                                ["height", ["height"]],\
                                ["insert", ["link", "picture", "imageList", "video", "hr"]],\
                                ["help", ["help"]]\
                            ],\
                            dialogsInBody: true,\
                            imageList: {\
                                endpoint: "daftar_gambar.php",\
                                fullUrlPrefix: "../gambar/",\
                                thumbUrlPrefix: "../gambar/"\
                            }\

                        });

                    $.upload = function(file) {
                        let out = new FormData();
                        out.append('file', file, file.name);

                        $.ajax({
                            method: 'POST',
                            url: 'upload_gambar.php',
                            contentType: false,
                            cache: false,
                            processData: false,
                            data: out,
                            success: function(img) {
                                $('#summernote').summernote('insertImage', img);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error(textStatus + " " + errorThrown);
                            }
                        });
                    };
                });
</script>
</body>

</html>