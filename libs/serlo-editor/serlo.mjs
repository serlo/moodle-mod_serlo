import {
  EditorWebComponent,
  defaultPlugins
} from "@serlo/editor-web-component";
import {
  call
} from "core/ajax";
import {
  addNotification
} from "core/notification";
import {
  addIconToContainerWithPromise
} from "core/loadingicon";
import {
  get_string
} from "core/str";

const preventLeavePage = (e) => {
  e.preventDefault();
}

const saveEditorState = async (serloid, state) => {
  const [result] = await Promise.allSettled(
    call([{
      methodname: "mod_serlo_update_serlo_state",
      args: {
        serloid,
        state: JSON.stringify(state)
      },
    },]),
  );
  if (result.status === "rejected") {
    const message = await get_string("serlosaveerror", "mod_serlo");
    addNotification({
      message: `${message}: ${result.reason.message}`,
      type: "error",
    });
  } else {
    const message = await get_string("serlosaved", "mod_serlo");
    // Remove preventLeavePage if state is saved!
    window.removeEventListener("beforeunload", preventLeavePage);
    addNotification({
      message,
      type: "info"
    });
  }
};

export const init = async (serloid) => {
  window.console.log("mod_serlo/serlo-lazy: init!");
  customElements.define("serlo-editor", EditorWebComponent);

  const editor = document.querySelector("serlo-editor");
  const saveButton = document.getElementById("mod_serlo_save");
  saveButton?.addEventListener("click", () =>
    saveEditorState(serloid, editor.currentState),
  );
  editor.addEventListener("state-changed", () => {
    saveButton?.classList.remove("disabled");
    window.addEventListener("beforeunload", preventLeavePage);
  });

  const loaderContainer = document.querySelector(".serlo.loader-wrapper");
  const loader = addIconToContainerWithPromise(loaderContainer);
  const [initialState] = await Promise.all(
    call([{
      methodname: "mod_serlo_get_serlo_state",
      args: {
        serloid
      },
    },]),
  );
  if (initialState) {
    editor.initialState = JSON.parse(initialState);
  }
  editor.plugins = defaultPlugins;
  editor.classList.remove("hidden");
  loader.resolve();
};