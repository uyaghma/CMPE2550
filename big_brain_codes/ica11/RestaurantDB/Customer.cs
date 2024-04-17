﻿using System;
using System.Collections.Generic;

namespace ica10.RestaurantDB;

public partial class Customer
{
    public int Cid { get; set; }

    public string Fname { get; set; } = null!;

    public string Lname { get; set; } = null!;

    public string Phone { get; set; } = null!;

    public string? Email { get; set; }

    public virtual ICollection<Order> Orders { get; } = new List<Order>();
}
