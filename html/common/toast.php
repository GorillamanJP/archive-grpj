<!-- 更新通知 -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bi bi-info-circle-fill me-2 text-primary"></i>
            <strong class="me-auto"><span id="notify_title"></span></strong>
            <small id="update_time"></small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <span id="update_msg_notify"></span>
        </div>
    </div>
</div>