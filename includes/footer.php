<footer class="footer text-center">
      <div class="container">
        <p class="text-muted">Copyright &copy; <?php echo date('Y')?> <a href="/"><?php echo $conf['title']?></a> <?php echo $conf['tongji']?> </p>
      </div>
    </footer>

<div class="modal fade" id="managerAuthModal" tabindex="-1" role="dialog" aria-labelledby="managerAuthTitle" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <form id="managerAuthForm" autocomplete="off">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="关闭"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="managerAuthTitle"><i class="fa fa-lock"></i> 站长授权</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" id="manager_csrf_token" name="manager_csrf_token" value="<?php echo isset($manager_csrf_token) ? $manager_csrf_token : ''; ?>">
          <div class="alert alert-danger hide" id="managerAuthError"></div>
          <div class="form-group">
            <label for="managerPassword">站长管理密码</label>
            <input type="password" class="form-control" id="managerPassword" name="manager_password" inputmode="numeric" autocomplete="off" maxlength="20" required placeholder="请输入站长管理密码">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-raised" data-dismiss="modal">取消</button>
          <button type="submit" class="btn btn-primary btn-raised" id="managerAuthSubmit">确认授权</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://s4.zstatic.net/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://s4.zstatic.net/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://s4.zstatic.net/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
<script src="https://s4.zstatic.net/ajax/libs/bootstrap-material-design/0.5.10/js/ripples.min.js"></script>
<script>
  $.material.init();
</script>
<script>
(function ($) {
  function showManagerAuthError(msg) {
    $('#managerAuthError').text(msg).removeClass('hide');
  }

  $(document).on('click', '#managerAuthOpen', function (e) {
    e.preventDefault();
    $('#managerAuthError').addClass('hide');
    $('#managerAuthModal').modal('show');
  });

  $('#managerAuthModal').on('shown.bs.modal', function () {
    $('#managerPassword').trigger('focus');
  });

  $('#managerAuthForm').on('submit', function (e) {
    e.preventDefault();
    var password = $.trim($('#managerPassword').val());
    var csrfToken = $('#manager_csrf_token').val();
    if (!password) {
      showManagerAuthError('请输入站长管理密码');
      return;
    }
    var submitBtn = $('#managerAuthSubmit');
    submitBtn.prop('disabled', true).text('验证中...');
    $.ajax({
      type: 'POST',
      url: 'ajax.php?act=managerAuth',
      data: {manager_password: password, manager_csrf_token: csrfToken},
      dataType: 'json'
    }).done(function (data) {
      if (data.code == 0) {
        window.location.reload();
      } else {
        showManagerAuthError(data.msg || '站长管理密码错误');
        $('#managerPassword').val('').trigger('focus');
      }
    }).fail(function () {
      showManagerAuthError('网络异常，请稍后重试');
    }).always(function () {
      submitBtn.prop('disabled', false).text('确认授权');
    });
  });

  $(document).on('click', '#managerLogout', function (e) {
    e.preventDefault();
    var csrfToken = $('#manager_csrf_token').val();
    $.ajax({
      type: 'POST',
      url: 'ajax.php?act=managerLogout',
      data: {manager_csrf_token: csrfToken},
      dataType: 'json'
    }).done(function () {
      window.location.reload();
    }).fail(function () {
      alert('退出失败，请刷新后重试');
    });
  });
})(jQuery);
</script>
<script>
(function () {
  if (!('serviceWorker' in navigator) || window.location.protocol !== 'https:') return;
  window.addEventListener('load', function () {
    navigator.serviceWorker.register('sw.js?v=<?php echo VERSION; ?>').catch(function () {});
  });
})();
</script>
