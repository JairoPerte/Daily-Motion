"use client";

import { useEffect, useRef, useState } from "react";
import { formatTime, getLocalISOWithTimezone } from "@/lib/utils/time";
import { CreateActivity } from "@/models/Activity/Activity";
import CategorySelect from "@/presentation/components/selects/CategorySelect";
import CategoryModal from "@/presentation/components/modals/CategoryModal";

import { createActivity } from "@/lib/api/activity/activity";

export default function TimerActivity() {
  const [isRunning, setIsRunning] = useState(false);
  const [elapsedTime, setElapsedTime] = useState(0);
  const [inputValue, setInputValue] = useState("");
  const [selectValue, setSelectValue] = useState("default");
  const [openDialog, setOpenDialog] = useState(false);

  const intervalRef = useRef<NodeJS.Timeout | null>(null);
  const MAX_CHARS = 100;

  const handleStart = (customCategoryId?: string) => {
    const categoryToUse = customCategoryId ?? selectValue;

    if (categoryToUse === "default") {
      setOpenDialog(true);
      return;
    }

    const startTimestamp = Date.now();
    localStorage.setItem("startTime", startTimestamp.toString());
    localStorage.setItem("activityName", inputValue);
    localStorage.setItem("categoryId", categoryToUse);
    setIsRunning(true);
  };

  const handleStop = async () => {
    const startedAt = localStorage.getItem("startTime");
    const finishedAt = getLocalISOWithTimezone(new Date());

    if (startedAt) {
      const activity: CreateActivity = {
        name: inputValue,
        categoryId: selectValue,
        startedAt: getLocalISOWithTimezone(new Date(parseInt(startedAt))),
        finishedAt,
      };

      await createActivity(activity);
    }

    localStorage.removeItem("startTime");
    localStorage.removeItem("activityName");
    localStorage.removeItem("categoryId");

    if (intervalRef.current) {
      clearInterval(intervalRef.current);
      intervalRef.current = null;
    }

    setIsRunning(false);
    setElapsedTime(0);
  };

  useEffect(() => {
    const storedStart = localStorage.getItem("startTime");
    const storedName = localStorage.getItem("activityName");
    const storedCategory = localStorage.getItem("categoryId");

    if (storedName) setInputValue(storedName);
    if (storedCategory) setSelectValue(storedCategory);

    if (storedStart) {
      const seconds = Math.floor((Date.now() - parseInt(storedStart)) / 1000);
      setElapsedTime(seconds);
      setIsRunning(true);
    }
  }, []);

  useEffect(() => {
    if (isRunning) {
      intervalRef.current = setInterval(() => {
        setElapsedTime((prev) => prev + 1);
      }, 1000);
    }

    return () => {
      if (intervalRef.current) {
        clearInterval(intervalRef.current);
        intervalRef.current = null;
      }
    };
  }, [isRunning]);

  const isValidInput = inputValue.length > 0 && inputValue.length <= MAX_CHARS;

  return (
    <div className="p-6 flex flex-col items-center justify-start relative overflow-visible">
      <CategoryModal
        isOpen={openDialog}
        onClose={() => setOpenDialog(false)}
        onChange={(id) => {
          setSelectValue(id);
          setOpenDialog(false);
          handleStart(id);
        }}
      />

      <div className="absolute top-20 text-6xl sm:text-7xl font-extrabold text-[#4A5568] z-0">
        {formatTime(elapsedTime)}
      </div>

      <div className="bg-white rounded-2xl shadow-xl z-10 w-full max-w-2xl p-6 mt-32 flex flex-col items-center gap-6 transition-all duration-300">
        {!isRunning && (
          <div className="w-full flex flex-col md:flex-row gap-4 items-center justify-center">
            <CategorySelect
              value={selectValue}
              onChange={(val) => setSelectValue(val)}
            />
            <div className="relative w-full">
              <input
                type="text"
                maxLength={MAX_CHARS}
                value={inputValue}
                onChange={(e) => setInputValue(e.target.value)}
                className={`w-full rounded py-2 pr-16 pl-4 border ${
                  isValidInput ? "border-gray-300" : "border-red-500"
                } shadow focus:outline-none`}
                placeholder="Nombra tu actividad..."
              />
              <span className="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">
                {inputValue.length}/{MAX_CHARS}
              </span>
            </div>
          </div>
        )}

        <div>
          {!isRunning ? (
            <button
              className={`mt-4 px-6 py-3 rounded text-white text-lg font-semibold transition ${
                isValidInput
                  ? "bg-[#2F855A] hover:bg-[#276749]"
                  : "bg-gray-300 cursor-not-allowed"
              }`}
              disabled={!isValidInput}
              onClick={() => {
                handleStart();
              }}
            >
              Empezar
            </button>
          ) : (
            <button
              className="mt-4 px-6 py-3 rounded text-white text-lg font-semibold bg-[#F6AD55] hover:bg-[#DD6B20] transition"
              onClick={handleStop}
            >
              Parar
            </button>
          )}
        </div>
      </div>
    </div>
  );
}
