import { createSlice } from "@reduxjs/toolkit";

export const redux = createSlice({
   name: "redux",
   initialState: {
      init: {},
      module: {},
      position: [],
   },
   reducers: {
      setInit: (state, { payload } = action) => {
         state.init = payload;
      },
      position: (state, { payload } = action) => {
         state.position = payload;
      },
      setModule: (state, { payload } = action) => {
         state.module = payload;
      },
   },
});
export const { init, setInit, setModule, position } = redux.actions;
export default redux.reducer;
