function get_fingerprint() {
    // 言語、画面サイズ、GPU情報、タイムゾーン、メモリ情報
    return navigator.language + screen.width + screen.height + get_GPU_info() + Intl.DateTimeFormat().resolvedOptions().timeZone + navigator.deviceMemory + navigator.hardwareConcurrency;
}

function get_GPU_info() {
    const canvas = document.createElement("canvas");
    const gl = canvas.getContext("webgl") || canvas.getContext("experimental-webgl");
    if (gl) {
        const debug_info = gl.getExtension("WEBGL_debug_renderer_info");
        return gl.getParameter(debug_info.UNMASKED_RENDERER_WEBGL);
    }
    return "unknown";
}