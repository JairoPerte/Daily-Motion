"use client";

import { useEffect, useState, useRef } from "react";
import { useSearchParams } from "next/navigation";

import { ChevronLeft, ChevronRight, FileText } from "lucide-react";

import { addDays, format, startOfWeek, parseISO, subHours } from "date-fns";
import { es } from "date-fns/locale";

import jsPDF from "jspdf";
import html2canvas from "html2canvas";

import { formatToDate, getActivityMinutes } from "@/lib/utils/time";
import { listActivities } from "@/lib/api/activity/activity";
import { Activity } from "@/models/Activity/Activity";

interface DayData {
  date: string;
  activities: Activity[];
}

export default function WeekActivityGraph() {
  const params = useSearchParams();
  const categoryId = params.get("category") || null;

  const [currentStartDate, setCurrentStartDate] = useState<Date>(() =>
    startOfWeek(new Date(), { weekStartsOn: 1 })
  );
  const [weekData, setWeekData] = useState<DayData[]>([]);

  const generateEmptyWeek = (start: Date): DayData[] =>
    Array.from({ length: 7 }, (_, i) => {
      const date = addDays(start, i);
      return {
        date: format(date, "yyyy-MM-dd"),
        activities: [],
      };
    });

  useEffect(() => {
    const fetchActivities = async () => {
      const startDateStr = format(currentStartDate, "yyyy-MM-dd");
      const rawActivities = await listActivities(
        startDateStr,
        "week",
        categoryId,
        null
      );

      const mappedWeek = generateEmptyWeek(currentStartDate).map((day) => {
        const dayActivities = rawActivities.filter((act) => {
          const startAdjusted = subHours(parseISO(act.startedAt), 2);
          return format(startAdjusted, "yyyy-MM-dd") === day.date;
        });
        return {
          ...day,
          activities: dayActivities,
        };
      });

      setWeekData(mappedWeek);
    };

    fetchActivities();
  }, [currentStartDate, categoryId]);

  const handlePrevWeek = () => setCurrentStartDate((prev) => addDays(prev, -7));
  const handleNextWeek = () => setCurrentStartDate((prev) => addDays(prev, 7));

  const handleDayClick = (day: DayData) => {
    if (day.activities.length === 0) return alert("Sin actividades");

    const summary = day.activities
      .map(
        (a) => `${a.name}: ${getActivityMinutes(a.startedAt, a.finishedAt)} min`
      )
      .join("\n");

    alert(summary);
  };

  const minutesPerDay = weekData.map((day) =>
    day.activities.reduce(
      (sum, act) => sum + getActivityMinutes(act.startedAt, act.finishedAt),
      0
    )
  );

  const maxMinutes = Math.max(...minutesPerDay);
  const maxHours = maxMinutes > 0 ? Math.ceil(maxMinutes / 60) : 1;

  const chartRef = useRef<HTMLDivElement>(null);

  const exportToPDF = async () => {
    if (!chartRef.current) return;

    const canvas = await html2canvas(chartRef.current, {
      scale: 2,
      useCORS: true,
    });

    const imgData = canvas.toDataURL("image/png");
    const pdf = new jsPDF("p", "mm", "a4");

    const imgProps = pdf.getImageProperties(imgData);
    const pdfWidth = pdf.internal.pageSize.getWidth();
    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

    pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);

    const blob = pdf.output("blob");
    const blobUrl = URL.createObjectURL(blob);

    window.open(blobUrl, "_blank");
  };

  return (
    <div
      ref={chartRef}
      className="bg-white p-4 rounded-xl shadow w-full max-w-4xl mx-auto pl-7"
    >
      {/* Header de la semana que maneja  */}
      <div className="flex justify-between items-center mb-4">
        <button onClick={handlePrevWeek}>
          <ChevronLeft className="w-5 h-5" />
        </button>
        <h2 className="text-lg font-semibold">
          Semana del{" "}
          {format(currentStartDate, "dd 'de' MMMM yyyy", { locale: es })}
          <button onClick={exportToPDF} title="Exportar a PDF">
            <FileText className="ml-3 w-5 h-5 text-gray-600 hover:text-black" />
          </button>
        </h2>
        <button onClick={handleNextWeek}>
          <ChevronRight className="w-5 h-5" />
        </button>
      </div>

      {/* GRÁFICO */}
      <div className="relative h-64 border-l border-b flex items-end pl-4">
        {/* LÍNEAS DE REFERENCIA Y */}
        {[...Array(maxHours)].map((_, i) => {
          const y = (1 - (i + 1) / maxHours) * 100;
          return (
            <div
              key={i}
              className="absolute left-0 right-0 border-t border-gray-200 text-gray-400 text-xs"
              style={{ top: `${y}%` }}
            >
              <span className="absolute -left-6">{i + 1}h</span>
            </div>
          );
        })}

        {/* BARRAS */}
        {weekData.map((day, index) => (
          <div
            key={index}
            className="flex-1 flex justify-center items-end cursor-pointer relative"
            onClick={() => handleDayClick(day)}
          >
            <div className="flex flex-col justify-end w-6 h-64">
              {day.activities.map((act, i) => {
                const minutes = getActivityMinutes(
                  act.startedAt,
                  act.finishedAt
                );
                const heightPct =
                  maxHours > 0 ? (minutes / (maxHours * 60)) * 100 : 0;

                return (
                  <div
                    key={i}
                    title={`${act.name}: Empezó ${formatToDate(
                      act.startedAt
                    )} y duró ${minutes} mins`}
                    className="bg-blue-400 rounded-sm mb-[1px] w-full transition-all duration-300"
                    style={{
                      height: heightPct > 0 ? `${heightPct}%` : "2px",
                    }}
                  />
                );
              })}
            </div>
          </div>
        ))}
      </div>

      {/* DÍAS DEBAJO DEL GRÁFICO */}
      <div className="flex pl-4 mt-1">
        {weekData.map((day, index) => (
          <div key={index} className="flex-1 flex justify-center">
            <span className="text-xs text-center">
              {format(new Date(day.date), "E dd", { locale: es })}
            </span>
          </div>
        ))}
      </div>
    </div>
  );
}
