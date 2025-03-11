"use client"

import { useRouter, useSearchParams } from "next/navigation"
import { Search, RotateCcw, MapPin } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { useState } from "react"
import { Label } from "@/components/ui/label"

export default function PropertyFilter() {
  const router = useRouter()
  const searchParams = useSearchParams();

  // State for filter inputs
  const [province, setProvince] = useState(searchParams.get("province") || "");
  const [title, setTitle] = useState(searchParams.get("title") || "");
  const [orderBy, setOrderBy] = useState(searchParams.get("order_by") || "");
  const [orderDirection, setOrderDirection] = useState(searchParams.get("order") || "");

  const provinces = [
    { id: "Bangkok", name: "Bangkok" },
    { id: "Chiang Mai", name: "Chiang Mai" },
    { id: "Phuket", name: "Phuket" },
    { id: "texas", name: "Texas" },
  ];
  const handleProvince = (province: string) => {
    console.log('province');
    const params = new URLSearchParams(searchParams.toString());
    setProvince(province);
    if (province) params.set("province", province);
    else params.delete("province");
    params.set('page', '1');
    router.push(`/${province}?${params.toString()}`);
  };

  // Handle search
  const handleSearch = () => {
    const params = new URLSearchParams(searchParams.toString());

    if (title) params.set("title", title);
    else params.delete("title");

    if (orderBy) params.set("order_by", orderBy);
    else params.delete("order_by");

    if (orderDirection) params.set("order", orderDirection);
    else params.delete("order");

    if (province) params.set("province", province);
    else params.delete("province");

    if (!title && !orderBy && !orderDirection) return;
    router.push(`?${params.toString()}`);
  };

  // Handle reset
  const handleReset = () => {
    setTitle("");
    setOrderBy("");
    setOrderDirection("");
    setProvince("");

    router.push("/");
  };

  return (
    <div className="space-y-6">
      {/* Container for Grid Layout */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        {/* Province Selection Dropdown */}
        <div className="bg-card border rounded-lg p-6 shadow-sm">
          <div className="flex items-center gap-2 mb-2">
            <MapPin className="h-5 w-5 text-primary" />
            <h3 className="text-lg font-semibold">Browse by Location</h3>
          </div>
          <p className="text-muted-foreground mb-4">Select a province to view properties in that area</p>

          <Select onValueChange={handleProvince} value={province}>
            <SelectTrigger className="w-full md:w-[300px] h-12 text-base">
              <SelectValue placeholder="Select a province" />
            </SelectTrigger>
            <SelectContent>
              {provinces.map((province) => (
                <SelectItem key={province.id} value={province.id}>
                  {province.name}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>

        {/* Other Filters */}
        <div className="bg-card border rounded-lg p-6 shadow-sm">
          <h3 className="text-lg font-semibold mb-4">Refine Your Search</h3>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            {/* Title Search */}
            <div className="space-y-2">
              <Label htmlFor="title">Property Title</Label>
              <Input
                id="title"
                placeholder="Search by title..."
                value={title}
                onChange={(e) => setTitle(e.target.value)}
              />
            </div>

            {/* Order By */}
            <div className="space-y-2">
              <Label htmlFor="orderBy">Order By</Label>
              <Select value={orderBy} onValueChange={setOrderBy}>
                <SelectTrigger id="orderBy">
                  <SelectValue placeholder="Order by" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="price">Price</SelectItem>
                  <SelectItem value="created_at">Listed Date</SelectItem>
                </SelectContent>
              </Select>
            </div>

            {/* Order Direction */}
            <div className="space-y-2">
              <Label htmlFor="orderDirection">Order</Label>
              <Select value={orderDirection} onValueChange={setOrderDirection}>
                <SelectTrigger id="orderDirection">
                  <SelectValue placeholder="Order direction" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="asc">Ascending</SelectItem>
                  <SelectItem value="desc">Descending</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>

          {/* Buttons */}
          <div className="flex flex-wrap gap-2 justify-end">
            <Button variant="outline" onClick={handleReset} className="flex items-center gap-1">
              <RotateCcw className="h-4 w-4" />
              Reset
            </Button>
            <Button onClick={handleSearch} className="flex items-center gap-1">
              <Search className="h-4 w-4" />
              Search
            </Button>
          </div>
        </div>
      </div>
    </div>
  )
}
