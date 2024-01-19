<script nonce="{{ csp_nonce() }}">
    if (window.Alpine) {
        console.warn(
            'Laravel Comments scripts were loaded after Alpine. ' +
            'Please ensure Alpine is loaded last so Laravel Comments can initialize first.'
        );
    }
</script>
@stack('comments-scripts')

<script nonce="{{ csp_nonce() }}">
    document.addEventListener("alpine:init", () => {
        window.Alpine.data("compose", ({ text, autofocus = false } = {}) => {
            // Store the editor as a non-reactive instance property
            let editor;

            return {
                text,

                init() {
                    if (editor) {
                        return;
                    }

                    const textarea = this.$el.querySelector("textarea");

                    if (!textarea) {
                        return;
                    }

                    this.loadEasyMDE().then(() => {
                        editor = new window.EasyMDE({
                            element: textarea,
                            hideIcons: [
                                "heading",
                                "image",
                                "preview",
                                "side-by-side",
                                "fullscreen",
                                "guide",
                            ],
                            spellChecker: false,
                            status: false,
                            insertTexts: {
                                link: ["[",  "](https://)"],
                            },
                        });

                        editor.value(this.text);

                        if (autofocus) {
                            editor.codemirror.focus();
                            editor.codemirror.setCursor(editor.codemirror.lineCount(), 0);
                        }

                        editor.codemirror.on("change", () => {
                            this.text = editor.value();
                        });
                    });
                },

                clear() {
                    editor.value("");
                },

                loadEasyMDE() {
                    if (window.EasyMDE) {
                        return Promise.resolve();
                    }

                    const loadScript = new Promise((resolve) => {
                        const script = document.createElement("script");
                        script.src = "https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js";
                        script.addEventListener("load", resolve);
                        document.getElementsByTagName("head")[0].appendChild(script);
                    });

                    const loadCss = new Promise((resolve) => {
                        const link = document.createElement("link");
                        link.type = "text/css";
                        link.rel = "stylesheet";
                        link.href = "https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css";
                        link.addEventListener("load", resolve);
                        document.getElementsByTagName("head")[0].appendChild(link);
                    });

                    return Promise.all([loadScript, loadCss]);
                },
            };
        });
    });
</script>
